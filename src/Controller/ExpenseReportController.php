<?php

namespace App\Controller;

use DateTime;
use App\Entity\Customer;
use App\Entity\Transaction;
use App\Entity\ExpenseReport;
use App\Form\TransactionType;
use App\Entity\TransactionLine;
use App\Form\ExpenseReportType;
use App\Entity\ExpenseReportLine;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


#[Route('/expenseReport', name: 'expenseReport_')]
class ExpenseReportController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    #[IsGranted('ROLE_TRESO')]
    public function showAll(EntityManagerInterface $entityManager): Response
    {
        $expenseReports = $entityManager->getRepository(ExpenseReport::class)->findAll();
        return $this->render('expense_report/showAll.html.twig', [
            'expenseReports' => $expenseReports,

        ]);
    }

    #[Route('/delete/{expenseReportID}', name: 'delete')]
    #[IsGranted('ROLE_TRESO')]
    public function delete(EntityManagerInterface $entityManager, $expenseReportID): Response
    {

        $expenseReport = $entityManager->getRepository(ExpenseReport::class)->findById($expenseReportID)[0];
        $entityManager->remove($expenseReport);
        $entityManager->flush();

        return $this->redirectToRoute('expenseReport_showAll', []);
    }

    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_USER')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $expenseReport = new ExpenseReport();
        $form = $this->createForm(ExpenseReportType::class, $expenseReport);
        $form->handleRequest($request);

        $customers = $entityManager->getRepository(Customer::class)->findAll();
        $i = 0;
        while ( ! isset($customer) and isset($customers[$i])) {
            if ($customers[$i]->getUser() == $this->getUser()) {
                $customer = $customers[$i];
;            }
            $i++;
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $expenseReport->setDate( new DateTime());
            $expenseReport->setCustomer($customer);
            if (isset($entityManager->getRepository(ExpenseReport::class)->findMaxDayExpenseReport(date("Ymd") * 100)[0])) {
                $nbtransaction = $entityManager->getRepository(ExpenseReport::class)->findMaxDayExpenseReport(date("Ymd") * 100)[0]['code'];
                $expenseReport->setCode($nbtransaction + 1);
            } else {
                $nbtransaction = 0;
                $expenseReport->setCode(date("Ymd") * 100 + $nbtransaction + 1);
            }
            foreach ($expenseReport->getExpenseReportLines() as $expenseReportLine) {
                $entityManager->persist($expenseReportLine);
            }
           
            foreach ($expenseReport->getExpenseReportRouteLines() as $expenseReportRouteLine) {
                if($expenseReportRouteLine->getDistance() <= 50){
                    $expenseReportRouteLine->setAmount($expenseReportRouteLine->getDistance()*0.15);
                }else{
                     $expenseReportRouteLine->setAmount($expenseReportRouteLine->getDistance()*0.20);
                }
                $entityManager->persist($expenseReportRouteLine);

            }

            $entityManager->persist($expenseReport);
            $entityManager->flush();

            $expenseReport = $entityManager->getRepository(ExpenseReport::class)->findExpenseReportByCode($expenseReport->getCode());

            $i = 0;

            foreach ($form->get('expenseReportLines') as $expenseReportLine) {
                $logoUpload = $expenseReportLine->get('document')->getData();
                if ($logoUpload) {
                    $document = 'expenseReportProof' . $expenseReport->getExpenseReportLines()[$i]->getId() . '.' . $logoUpload[0]->guessExtension();
                    $expenseReport->getExpenseReportLines()[$i]->setDocument('public/build/expenseReportLine/proof/' . $document);
                    try {
                        $logoUpload[0]->move(
                            'public/build/expenseReportLine/proof',
                            $document
                        );
                    } catch (FileException $e) {
                    }
                }
                $entityManager->persist($expenseReport->getExpenseReportLines()[$i]);
                $i++;
            }
            $entityManager->flush();

            return $this->redirectToRoute('expenseReport_show', ['expenseReportID' => $expenseReport->getId()]);
        }

        return $this->render('expense_report/new.html.twig', [
            'expenseReport' => $expenseReport,
            'form' => $form->createView(),
        ]);
       
    }

    #[Route('/edit/{expenseReportID}', name: 'edit')]
    #[IsGranted('ROLE_USER')]
    public function edit(EntityManagerInterface $entityManager, $expenseReportID, Request $request): Response
    {
        $expenseReport = $entityManager->getRepository(ExpenseReport::class)->findExpenseReportById($expenseReportID);

        $customers = $entityManager->getRepository(Customer::class)->findAll();
        $i = 0;
        while (!isset($customer) and isset($customers[$i])) {
            if ($customers[$i]->getUser() == $this->getUser()) {
                $customer = $customers[$i];;
            }
            $i++;
        }
        if ($customer == $expenseReport->getCustomer() or $this->isGranted("ROLE_TRESO")) {
            $form = $this->createForm(ExpenseReportType::class, $expenseReport);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $expenseReport->setDate(new DateTime());
                $expenseReport->setCustomer($customer);
                if (isset($entityManager->getRepository(ExpenseReport::class)->findMaxDayExpenseReport(date("Ymd") * 100)[0])) {
                    $nbtransaction = $entityManager->getRepository(ExpenseReport::class)->findMaxDayExpenseReport(date("Ymd") * 100)[0]['code'];
                    $expenseReport->setCode($nbtransaction + 1);
                } else {
                    $nbtransaction = 0;
                    $expenseReport->setCode(date("Ymd") * 100 + $nbtransaction + 1);
                }
                foreach ($expenseReport->getExpenseReportLines() as $expenseReportLine) {
                    $entityManager->persist($expenseReportLine);
                }

                foreach ($expenseReport->getExpenseReportRouteLines() as $expenseReportRouteLine) {
                    if ($expenseReportRouteLine->getDistance() <= 50) {
                        $expenseReportRouteLine->setAmount($expenseReportRouteLine->getDistance() * 0.15);
                    } else {
                        $expenseReportRouteLine->setAmount($expenseReportRouteLine->getDistance() * 0.20);
                    }
                    $entityManager->persist($expenseReportRouteLine);
                }

                $entityManager->persist($expenseReport);
                $entityManager->flush();

                $expenseReport = $entityManager->getRepository(ExpenseReport::class)->findExpenseReportByCode($expenseReport->getCode());

                $i = 0;

                foreach ($form->get('expenseReportLines') as $expenseReportLine) {
                    $logoUpload = $expenseReportLine->get('document')->getData();
                    if ($logoUpload) {
                        $document = 'expenseReportProof' . $expenseReport->getExpenseReportLines()[$i]->getId() . '.' . $logoUpload[0]->guessExtension();
                        $expenseReport->getExpenseReportLines()[$i]->setDocument('public/build/expenseReportLine/proof/' . $document);
                        try {
                            $logoUpload[0]->move(
                                'public/build/expenseReportLine/proof',
                                $document
                            );
                        } catch (FileException $e) {
                        }
                    }
                    $entityManager->persist($expenseReport->getExpenseReportLines()[$i]);
                    $i++;
                }
                $entityManager->flush();

                return $this->redirectToRoute('expenseReport_show', ['expenseReportID' => $expenseReport->getId()]);
            }

            return $this->render('expense_report/edit.html.twig', [
                'expenseReport' => $expenseReport,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('account');
        }
    }

    #[Route('/show/{expenseReportID}', name: 'show')]
    #[IsGranted('ROLE_USER')]
    public function show(EntityManagerInterface $entityManager, $expenseReportID): Response
    {
        $customers = $entityManager->getRepository(Customer::class)->findAll();
        $i = 0;
        while (!isset($customer) and isset($customers[$i])) {
            if ($customers[$i]->getUser() == $this->getUser()) {
                $customer = $customers[$i];;
            }
            $i++;
        }
        $expenseReport = $entityManager->getRepository(ExpenseReport::class)->findExpenseReportById($expenseReportID);
        if ($customer == $expenseReport->getCustomer() or $this->isGranted("ROLE_TRESO")) {
        return $this->render('expense_report/show.html.twig', [
            'expenseReport' => $expenseReport,
        ]);
        } else {
            return $this->redirectToRoute('account');
        }
    }

    #[Route('/download/{expenseReportLineID}', name: 'download')]
    #[IsGranted('ROLE_USER')]
    public function download(EntityManagerInterface $entityManager,  $expenseReportLineID)
    {
        $expenseReportLine = $entityManager->getRepository(ExpenseReportLine::class)->findById($expenseReportLineID);
        $customers = $entityManager->getRepository(Customer::class)->findAll();
        $i = 0;
        while (!isset($customer) and isset($customers[$i])) {
            if ($customers[$i]->getUser() == $this->getUser()) {
                $customer = $customers[$i];;
            }
            $i++;
        }
        if($customer == $expenseReportLine->getExpenseReport()->getCustomer() or $this->isGranted("ROLE_TRESO")){
            $finaleFile = $expenseReportLine->getDocument();

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($finaleFile) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($finaleFile));
            readfile($finaleFile);

            return $this->redirectToRoute('expenseReport_show', ['expenseReportID' => $expenseReportLine->getExpenseReport()->getId()]);
         }else{
            return $this->redirectToRoute('account');
         }
    }

    #[Route('/comfirm/{expenseReportID}', name: 'comfirm')]
    #[IsGranted('ROLE_TRESO')]
    public function comfirm(EntityManagerInterface $entityManager, Request $request, $expenseReportID): Response
    {
        $expenseReport = $entityManager->getRepository(ExpenseReport::class)->findById($expenseReportID)[0];

        $expenseReport->setComfirm(true);
       

        $transaction = new Transaction();
        $transaction->setExercice($expenseReport->getExercice());
        $expenseReport->setTransaction($transaction);
        if (isset($entityManager->getRepository(Transaction::class)->findMaxDayTransaction(date("Ymd") * 100)[0]['code'])) {
            $nbtransaction = $entityManager->getRepository(Transaction::class)->findMaxDayTransaction(date("Ymd") * 100)[0]['code'];
        } else {
            $nbtransaction = 0;
        }
        $transaction->setCode(intval(date("Ymd")) * 100 + $nbtransaction + 1);
        $transaction->setClosure(false);


        $transactionline = new TransactionLine();
        $transactionline->setTransaction($transaction);
        $transactionline->setDate(new \DateTime());
        $transactionline->setAmount(ExpenseReportController::expenseReportTotale($expenseReport));
        $transactionline->setLabel("NDF-" . $expenseReport->getCode());

        $entityManager->persist($transaction);
        $entityManager->persist($transactionline);
        $entityManager->persist($expenseReport);
        $entityManager->flush();

        return $this->redirectToRoute('expenseReport_showAll');
    }

    public function expenseReportTotale($expenseReport)
    {
        $nbTrajet = count($expenseReport->getExpenseReportRouteLines());
        $nbFrais = count($expenseReport->getExpenseReportLines());
        $totale = 0;

        for ($i = 0; $i < $nbTrajet; $i++) {

            if ($expenseReport->getExpenseReportRouteLines()[$i]->getAmount() != null and  $expenseReport->getExpenseReportRouteLines()[$i]->getRepayGrid()->getAmount() == null ){  
                $add = $expenseReport->getExpenseReportRouteLines()[$i]->getAmount();
            } else if($expenseReport->getExpenseReportRouteLines()[$i]->getAmount() == null and  $expenseReport->getExpenseReportRouteLines()[$i]->getRepayGrid()->getAmount() != null){
                $add = $expenseReport->getExpenseReportRouteLines()[$i]->getRepayGrid()->getAmount();
            } else {
                $add = 0;
            }

            $totale += $add;
            
        }

        for ($i = 0; $i < $nbFrais; $i++) {
            if ($expenseReport->getExpenseReportLines()[$i]->getAmount()) {
                $totale += $expenseReport->getExpenseReportLines()[$i]->getAmount();
            }
            
        }

        return $totale;
    }

}

