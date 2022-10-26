<?php

namespace App\Controller;

use DateTime;
use App\Entity\Invoice;
use App\Entity\Customer;
use App\Entity\Impression;
use App\Entity\InvoiceLine;
use App\Form\ImpressionType;
use App\Entity\CatalogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/impression', name: 'impression_')]
class ImpressionController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    #[IsGranted('ROLE_BF')]
    public function showAll(EntityManagerInterface $entityManager, Request $request): Response
    {
        $impressions = $entityManager->getRepository(Impression::class)->findAllinOrder();

         if ($request->isMethod('post')) {
             $posts = $request->request->all();
             if ($posts['customer']) {
                 $impressions = array_intersect($impressions, $entityManager->getRepository(Impression::class)->findAllByCustomer($posts['customer']));
             }
            if ($posts['format'] && $posts['format'] != 'null') {
                $impressions = array_intersect($impressions, $entityManager->getRepository(Impression::class)->findAllByFormat($posts['format']));
            }
             if ($posts['rectoVerso'] && $posts['rectoVerso'] != 'null') {
                 $impressions = array_intersect($impressions, $entityManager->getRepository(Impression::class)->findAllIfRV($posts['rectoVerso']));
             }
            if ($posts['couleur'] && $posts['couleur'] != 'null') {
                $impressions = array_intersect($impressions, $entityManager->getRepository(Impression::class)->findAllIfCouleur($posts['couleur']));
            }
         }

        return $this->render('impression/showAll.html.twig', [
            'impressions' => $impressions,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $impression = new Impression();
        $form = $this->createForm(ImpressionType::class, $impression, ['liste_customer' =>  $entityManager->getRepository(Customer::class)->findAllImpressionAccess()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $impression->setDatetime(new DateTime());

            $invoiceLine = new InvoiceLine();

            if($impression->isFactureFinDuMois()){ //si facture fin mois
                if($impression->getFormat() == 'plastification'){
                    $impression->setCouleur(false);
                    $impression->setRectoVerso(false);
                    $invoiceLine->setCatalogService($entityManager->getRepository(CatalogService::class)->findByCode('plastification'));
                } else {
                    if($impression->isRectoVerso()){
                        if($impression->isCouleur()){
                            $invoiceLine->setCatalogService($entityManager->getRepository(CatalogService::class)->findByCode($impression->getFormat().'RV'.'couleur'));
                        }else{
                            $invoiceLine->setCatalogService($entityManager->getRepository(CatalogService::class)->findByCode($impression->getFormat() . 'RV'));
                        }
                    } else {
                        if ($impression->isCouleur()) {
                            $invoiceLine->setCatalogService($entityManager->getRepository(CatalogService::class)->findByCode($impression->getFormat() .'R' . 'couleur'));
                        } else {
                            $invoiceLine->setCatalogService($entityManager->getRepository(CatalogService::class)->findByCode($impression->getFormat() . 'R'));
                        }
                    }
                }
                $invoice = $entityManager->getRepository(Invoice::class)->findCurrentInvoiceImpressionOfCustomer($impression->getCustomer());
                if($invoice = null){
                    $invoice = new Invoice();
                    $invoice->setAcquitted(false);
                    $invoice->setComfirm(false);
                    $invoice->setReady(false);
                    $invoice->setCode('impression mois'.date('m'));
                    $invoice->setCustomer($impression->getCustomer());
                    $invoice->setCreationDate(new Datetime());
                }
                
            }else{ // donc facture direct 
                $invoiceLine = new InvoiceLine();
                if ($impression->getFormat() == 'plastification') {
                    $impression->setCouleur(false);
                    $impression->setRectoVerso(false);
                    $invoiceLine->setCatalogService($entityManager->getRepository(CatalogService::class)->findByCode('plastification'));
                } else {
                    if ($impression->isRectoVerso()) {
                        if ($impression->isCouleur()) {
                            $invoiceLine->setCatalogService($entityManager->getRepository(CatalogService::class)->findByCode($impression->getFormat() . 'RV' . 'couleur'));
                        } else {
                            $invoiceLine->setCatalogService($entityManager->getRepository(CatalogService::class)->findByCode($impression->getFormat() . 'RV'));
                        }
                    } else {
                        if ($impression->isCouleur()) {
                            $invoiceLine->setCatalogService($entityManager->getRepository(CatalogService::class)->findByCode($impression->getFormat() . 'R' . 'couleur'));
                        } else {
                            $invoiceLine->setCatalogService($entityManager->getRepository(CatalogService::class)->findByCode($impression->getFormat() . 'R'));
                        }
                    }
                }

                $invoice = new Invoice();
                $invoice->setCategory('Fin Mois');
                $invoice->setCustomer($impression->getCustomer());
                $invoice->setCreationDate(new Datetime());
                
                $invoice->setAcquitted(false);
                $invoice->setComfirm(false);
                $invoice->setReady(false);
                
            }
            $impression->setInvoice($invoice);
            $invoiceLine->setInvoice($invoice);

            $entityManager->persist($invoice);
            $entityManager->persist($invoiceLine);
            $entityManager->persist($impression);
            $entityManager->flush();
            return $this->redirectToRoute('impression_validation', []);
        }
        return $this->render('impression/new.html.twig', [
            'impression' => $impression,
            'form' => $form->createView(),

        ]);
    }

    #[Route('/validation', name: 'validation')]
    public function validation(): Response
    {
        return $this->render('impression/validation.html.twig', [
        ]);
    }

    #[Route('/delete/{impressionId}', name: 'delete')]
    #[IsGranted('ROLE_TRESO')]
    public function delete(EntityManagerInterface $entityManager, $impressionId): Response
    {

        $impression = $entityManager->getRepository(Impression::class)->findById($impressionId)[0];
        $entityManager->remove($impression);
        $entityManager->flush();

        return $this->redirectToRoute('impression_showAll', []);
    }
}
