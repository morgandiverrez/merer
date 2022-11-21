<?php

namespace App\Controller;

use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Invoice;
use App\Form\InvoiceType;
use App\Entity\Federation;
use App\Entity\Institution;
use App\Entity\Transaction;
use App\Entity\TransactionLine;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/invoice', name: 'invoice_')]
class InvoiceController extends AbstractController
{

    #[Route('/', name: 'showAll')]
    #[IsGranted('ROLE_TRESO')]
    public function showAll(EntityManagerInterface $entityManager, Request $request): Response
    {

        $invoices = $entityManager->getRepository(Invoice::class)->findAll();
        $totals = array();
        foreach ($invoices as $invoice) {
            array_push($totals, (new InvoiceController)->invoiceTotale($invoice));
        }
      

        return $this->render('invoice/showAll.html.twig', [
            'invoices' => $invoices,
            'totals' => $totals,
        ]);
    }

  

    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_TRESO')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {

        $invoice = new Invoice();
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (isset($entityManager->getRepository(Invoice::class)->findMaxDayTransaction(date("Ymd") * 100)[0])) {
                $nbinvoice = $entityManager->getRepository(Invoice::class)->findMaxDayTransaction(date("Ymd") * 100)[0]['code'];
                $invoice->setCode($nbinvoice + 1);
            } else {
                $nbinvoice = 0;
                $invoice->setCode(date("Ymd") * 100 + $nbinvoice + 1);
            }
            $entityManager->persist($invoice);
            $entityManager->flush();
            return $this->redirectToRoute('invoice_show', ['invoiceId' => $invoice->getId()]); 
        }

        return $this->render('invoice/new.html.twig', [
            'invoice' => $invoice,
            'form' => $form->createView(),
        ]);
    }



   



    #[Route('/delete/{invoiceID}', name: 'delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(EntityManagerInterface $entityManager, $invoiceID): Response
    {

        $invoice = $entityManager->getRepository(Invoice::class)->findById($invoiceID)[0];
        $entityManager->remove($invoice);
        $entityManager->flush();

        return $this->redirectToRoute('invoice_showAll');
    }


    #[Route('/edit/{invoiceID}', name: 'edit')]
    #[IsGranted('ROLE_TRESO')]
    public function edit(EntityManagerInterface $entityManager, Request $request, $invoiceID): Response
    {

        $invoice = $entityManager->getRepository(Invoice::class)->findById($invoiceID);
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (isset($entityManager->getRepository(Invoice::class)->findMaxDayTransaction(date("Ymd") * 100)[0])) {
                $nbinvoice = $entityManager->getRepository(Invoice::class)->findMaxDayTransaction(date("Ymd") * 100)[0]['code'];
                $invoice->setCode($nbinvoice + 1);
            } else {
                $nbinvoice = 0;
                $invoice->setCode(date("Ymd") * 100 + $nbinvoice + 1);
            }
            $entityManager->persist($invoice);
            $entityManager->flush();
            return $this->redirectToRoute('invoice_show', ['invoiceId' => $invoice->getId()]);
        }

        return $this->render('invoice/edit.html.twig', [
            'invoice' => $invoice,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/comfirm/{invoiceId}', name: 'comfirm')]
    #[IsGranted('ROLE_TRESO')]
    public function comfirm(EntityManagerInterface $entityManager, Request $request, $invoiceId): Response
    {
        $invoice = $entityManager->getRepository(Invoice::class)->findById($invoiceId);

        $invoice->setComfirm(true);
        $invoice->setCreationDate(new DateTime());

        $transaction = new Transaction();
        $transaction->setExercice($invoice->getExercice());
        $invoice->setTransaction($transaction);
        if (isset($entityManager->getRepository(Transaction::class)->findMaxDayTransaction(date("Ymd") * 100)[0]['code'])){
            $nbtransaction = $entityManager->getRepository(Transaction::class)->findMaxDayTransaction(date("Ymd") * 100)[0]['code'];
    }
        else{
            $nbtransaction = 0;
        }
        $transaction->setCode(intval(date("Ymd")) * 100 + $nbtransaction + 1);
        $transaction->setClosure(false);


        $transactionline = new TransactionLine();
        $transactionline->setTransaction($transaction);
        $transactionline->setDate(new \DateTime());
        $transactionline->setAmount(InvoiceController::invoiceTotale($invoice));
        $transactionline->setLabel("Fact-" . $invoice->getCode());
        $transactionline->setChartofaccounts($invoice->getCustomer()->getChartofaccounts());

        $entityManager->persist($transaction);
        $entityManager->persist($transactionline);
        $entityManager->persist($invoice);
        $entityManager->flush();

        return $this->redirectToRoute('invoice_showAll');
    }

    #[Route('/show/{invoiceID}', name: 'show')]
    #[IsGranted('ROLE_USER')]
    public function show(EntityManagerInterface $entityManager, $invoiceID): Response
    {
        $invoice = $entityManager->getRepository(Invoice::class)->findById($invoiceID);

        return $this->render('invoice/show.html.twig', [
            'invoice' => $invoice,

        ]);
    }

    #[Route('/pdf/{invoiceId}', name: 'invoicePDF')]
    #[IsGranted('ROLE_USER')]
    public function invoicePDF(EntityManagerInterface $entityManager, $invoiceId){
        $invoice = $entityManager->getRepository(Invoice::class)->findById($invoiceId);
        $user = $this->getUser();

        if($user != $invoice->getCustomer()->getUser() and ! $this->isGranted("ROLE_TRESO")){
            return $this->redirectToRoute('profil_show');
        }
        $federation = $entityManager->getRepository(Federation::class)->findBySocialReason("Fédé B")[0];
     
        $institution = $entityManager->getRepository(Institution::class)->findHeadquarterById($federation->getId());

        $options = new Options();
        $options->set('defaultFont', 'Roboto');
        $options->setIsRemoteEnabled(true);

        $dompdf = new Dompdf($options);
     $html = $this->renderView('invoice/templateInvoice.html.twig', [
            'invoice' => $invoice,
            'federation'=>$federation,
            'institution'=>$institution,
            'total'=> InvoiceController::invoiceTotale($invoice),
        ]);


        $dompdf->loadHtml($html);


        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        //file_put_contents("../files/invoices/Fac-".$invoice->getCode().".pdf", $dompdf->output());
        $dompdf->stream("Fac-".$invoice->getCode(), [
            "Attachment" => false
        ]);

        exit(0);
    }

    
    public function invoiceTotale($invoice)
    {
        $nbInvoiceLine = count($invoice->getInvoiceLines());
        $totale = 0;

        for ($i = 0; $i < $nbInvoiceLine; $i++) {
            if ($invoice->getInvoiceLines()[$i]->getCatalogservice()) {
                $totale += $invoice->getInvoiceLines()[$i]->getCatalogservice()->getAmountTtc();
            }
            $totale -= $invoice->getInvoiceLines()[$i]->getDiscount();
        }

        return $totale;
    }

    public function invoicPaymentdeadlineTotale($invoice)
    {
        $nbPaymentdeadlineeTotale = count($invoice->getPaymentdeadlines());
        $totale = 0;

        for ($i = 0; $i < $nbPaymentdeadlineeTotale; $i++) {
            $totale += $invoice->getPaymentdeadlines()[$i]->getExpectedAmount();
        }

        return $totale;
    }
}
