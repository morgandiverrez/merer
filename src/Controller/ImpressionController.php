<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use App\Entity\Invoice;
use App\Entity\Customer;
use App\Entity\Impression;
use App\Entity\InvoiceLine;
use App\Entity\Transaction;
use App\Form\ImpressionType;
use App\Entity\CatalogService;
use App\Entity\PaymentDeadline;
use App\Entity\TransactionLine;
use App\Controller\InvoiceController;
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

            if(! $impression->isDejaPaye() &&  $impression->isFactureFinDuMois()){
                echo('pas payé et fin mois');
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
                    echo('invocie null');
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
                $invoice->setCategory('Impression');
                $invoice->setCode('impression mois' . date('m'));
                $invoice->setCustomer($impression->getCustomer());
                $invoice->setCreationDate(new Datetime());
                if($impression->isDejaPaye()){
                    $invoice->setAcquitted(true);
                    $invoice->setComfirm(true);
                    $invoice->setReady(false);
                   
                    
                    $transaction = new Transaction();
                    $invoice->setTransaction($transaction);
                    if (isset($entityManager->getRepository(Transaction::class)->findMaxDayTransaction(date("Ymd") * 100)[0])){
                         $nbtransaction = $entityManager->getRepository(Transaction::class)->findMaxDayTransaction(date("Ymd") * 100)[0]['code'];
                        $transaction->setCode($nbtransaction + 1);
                    }else {
                        $nbtransaction = 0;
                         $transaction->setCode(date("Ymd") * 100 + 1);
                    }
                    $transaction->setClosure(true);
                    $invoice->setCode($transaction->getCode());
                    
                    $transactionLine = new TransactionLine();
                    $transactionLine->setTransaction($transaction);
                    $transactionLine->setDate(new \DateTime());
                    $totale = (new InvoiceController)->invoiceTotale($invoice);
                    echo($totale);
                    $transactionLine->setAmount($totale);
                    $transactionLine->setLabel("Fact-" . $transaction->getCode());
                    $transactionLine->setChartofaccounts($invoice->getCustomer()->getChartofaccounts());

                   
                    $paymentDeadline = new PaymentDeadline();
                    $paymentDeadline->setExpectedAmount($totale);
                    $date = new DateTime();
                    $paymentDeadline->setExpectedPaymentDate($date);
                    $paymentDeadline->setExpectedMeans("espece");
                    $paymentDeadline->setActualPaymentDate($date);
                    $paymentDeadline->setActualAmount($totale);
                    $paymentDeadline->setActualMeans("espece");
                    $paymentDeadline->setInvoice($invoice);

                    $entityManager->persist($paymentDeadline);
                    $entityManager->persist($transaction);
                    $entityManager->persist($transactionLine);

                }else{
                    $invoice->setAcquitted(false);
                    $invoice->setComfirm(false);
                    $invoice->setReady(false);
                }
            }
            $impression->setInvoice($invoice);
            $invoiceLine->setInvoice($invoice);

            $entityManager->persist($invoice);
            $entityManager->persist($invoiceLine);
            $entityManager->persist($impression);
            echo ("paymentdeadline avant");
            $entityManager->flush();
            return $this->redirectToRoute('impression_validation', []);
        }
        echo ('1');
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
