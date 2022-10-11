<?php

namespace App\Controller;

use DateTime;
use App\Entity\Invoice;
use App\Entity\Impression;
use App\Entity\InvoiceLine;
use App\Form\ImpressionType;
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
        $impressions = $entityManager->getRepository(Impression::class)->findAll();

         if ($request->isMethod('post')) {
             $posts = $request->request->all();
             if ($posts['association']) {
                 $impressions = array_intersect($impressions, $entityManager->getRepository(Impression::class)->findAllByAssociation($posts['association']));
             }
             if ($posts['format']) {
                 $impressions = array_intersect($impressions, $entityManager->getRepository(Impression::class)->findAllByFormat($posts['format']));
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
        $form = $this->createForm(ImpressionType::class, $impression);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $impression->setDatetime(new DateTime());
            $entityManager->persist($impression);
            $entityManager->flush();

            if(! $impression->isDejaPaye() &&  $impression->isFactureFinDuMois()){
                $invoiceLine = new InvoiceLine();
                if($impression->getFormat() == 'plastification'){
                    $invoiceLine->setCatalogService($entityManager->getRepository(CatalogService::class)->findByCode('plastification')[0]);
                } else {
                    if($impression->isRectoVerso()){
                        if($impression->isCouleur()){
                            $invoiceLine->setCatalogService($entityManager->getRepository(CatalogService::class)->findByCode($impression->getFormat().'RV'.'couleur')[0]);
                        }else{
                            $invoiceLine->setCatalogService($entityManager->getRepository(CatalogService::class)->findByCode($impression->getFormat() . 'RV')[0]);
                        }
                    } else {
                        if ($impression->isCouleur()) {
                            $invoiceLine->setCatalogService($entityManager->getRepository(CatalogService::class)->findByCode($impression->getFormat() .'R' . 'couleur')[0]);
                        } else {
                            $invoiceLine->setCatalogService($entityManager->getRepository(CatalogService::class)->findByCode($impression->getFormat() . 'R')[0]);
                        }
                    }
                }
                $invoice = $entityManager->getRepository(Invoice::class)->findCurrentInvoiceImpressionOfAssociation($impression->getAssociation()->getSigle())[0];
                if($invoice = null){
                    $invoice = new Invoice();
                    $invoice->setAssociation($impression->getAssociation());
                    $invoice->setCreationDate(new Datetime());
                }
                $invoiceLine->setInvoice($invoice);
            }else{
                $invoiceLine = new InvoiceLine();
                if ($impression->getFormat() == 'plastification') {
                    $invoiceLine->setCatalogService($entityManager->getRepository(CatalogService::class)->findByCode('plastification')[0]);
                } else {
                    if ($impression->isRectoVerso()) {
                        if ($impression->isCouleur()) {
                            $invoiceLine->setCatalogService($entityManager->getRepository(CatalogService::class)->findByCode($impression->getFormat() . 'RV' . 'couleur')[0]);
                        } else {
                            $invoiceLine->setCatalogService($entityManager->getRepository(CatalogService::class)->findByCode($impression->getFormat() . 'RV')[0]);
                        }
                    } else {
                        if ($impression->isCouleur()) {
                            $invoiceLine->setCatalogService($entityManager->getRepository(CatalogService::class)->findByCode($impression->getFormat() . 'R' . 'couleur')[0]);
                        } else {
                            $invoiceLine->setCatalogService($entityManager->getRepository(CatalogService::class)->findByCode($impression->getFormat() . 'R')[0]);
                        }
                    }
                }
                $invoice = new Invoice();
                $invoice->setAssociation($impression->getAssociation());
                $invoice->setCreationDate(new Datetime());
                $invoice->setCode($entityManager->getRepository(Invoice::class)->findLastFAEI() + 1);
                if($impression->isDejaPaye()){
                    $invoice->setAcquitted(true);
                }
                $invoiceLine->setInvoice($invoice);
            }
            return $this->redirectToRoute('impression_validation', []);
        }

        return $this->render('impression/new.html.twig', [
            'impression' => $impression,
            'form' => $form->createView(),

        ]);
    }

    #[Route('/validation', name: 'validation')]
    public function validation(EntityManagerInterface $entityManager, Request $request): Response
    {
        return $this->render('impression/validation.html.twig', [
        ]);
    }

    #[Route('/delete/{impressionId}', name: 'delete')]
    #[IsGranted('ROLE_BF')]
    public function delete(EntityManagerInterface $entityManager, $impressionId): Response
    {

        $impression = $entityManager->getRepository(Impression::class)->findById($impressionId)[0];
        $entityManager->remove($impression);
        $entityManager->flush();

        return $this->redirectToRoute('impression_showAll', []);
    }
}
