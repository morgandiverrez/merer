<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Transaction;
use App\Entity\ExpenseReport;
use App\Entity\TransactionLine;
use App\Entity\ExpenseReportLine;
use App\Form\TransactionLineType;
use App\Form\ExpenseReportLineType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


#[Route('/expenseReportLine', name: 'expenseReportLine_')]
class ExpenseReportLineController extends AbstractController
{

    #[Route('/new/{expenseReportID}', name: 'new')]
    #[IsGranted('ROLE_USER')]
    public function new(EntityManagerInterface $entityManager, $expenseReportID, Request $request): Response
    {
        $expenseReportLine = new ExpenseReportLine();
        $expenseReport = $entityManager->getRepository(ExpenseReport::class)->findExpenseReportById($expenseReportID);

        $customers = $entityManager->getRepository(Customer::class)->findAll();
        $i = 0;
        while (!isset($customer) and isset($customers[$i])) {
            if ($customers[$i]->getUser() == $this->getUser()) {
                $customer = $customers[$i];;
            }
            $i++;
        }

        if (($customer == $expenseReport->getCustomer() or $this->isGranted("ROLE_TRESO")) and !$expenseReport->isComfirm()) {
            $form = $this->createForm(ExpenseReportLineType::class, $expenseReportLine);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $expenseReport = $entityManager->getRepository(ExpenseReport::class)->findExpenseReportById($expenseReportID);
                $expenseReportLine->setExpenseReport($expenseReport);
                $entityManager->persist($expenseReportLine);
                $entityManager->flush();

                $logoUpload = $form->get('document')->getData();
                if ($logoUpload) {
                    $document = 'expenseReportProof' . $expenseReportLine->getId() . '.' . $logoUpload[0]->guessExtension();
                    $expenseReportLine->setDocument('public/build/expenseReportLine/proof/' . $document);
                    try {
                        $logoUpload[0]->move(
                            'public/build/expenseReportLine/proof',
                            $document
                        );
                    } catch (FileException $e) {
                    }
                }
                $expenseReportLine->setExpenseReport($expenseReport);
                $entityManager->persist($expenseReportLine);
                $entityManager->flush();

                return $this->redirectToRoute('expenseReport_show', ['expenseReportID' => $expenseReport->getId()]);
            }



            return $this->render('expense_report_line/edit.html.twig', [
                'expenseReportLine' => $expenseReportLine,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('account');
        }
    }


    #[Route('/edit/{expenseReportID}/{expenseReportLineID}', name: 'edit')]
    #[IsGranted('ROLE_USER')]
    public function edit(EntityManagerInterface $entityManager, $expenseReportID, $expenseReportLineID, Request $request): Response
    {
        $expenseReportLine = $entityManager->getRepository(ExpenseReportLine::class)->findById($expenseReportLineID)[0];

        $expenseReport = $entityManager->getRepository(ExpenseReport::class)->findExpenseReportById($expenseReportID);

        $customers = $entityManager->getRepository(Customer::class)->findAll();
        $i = 0;
        while (!isset($customer) and isset($customers[$i])) {
            if ($customers[$i]->getUser() == $this->getUser()) {
                $customer = $customers[$i];;
            }
            $i++;
        }

        if (($customer == $expenseReport->getCustomer() or $this->isGranted("ROLE_TRESO")) and !$expenseReport->isComfirm()) {
            $form = $this->createForm(ExpenseReportLineType::class, $expenseReportLine);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $expenseReport = $entityManager->getRepository(ExpenseReport::class)->findExpenseReportById($expenseReportID);
                $expenseReportLine->setExpenseReport($expenseReport);
                $entityManager->persist($expenseReportLine);
                $entityManager->flush();

                $logoUpload = $form->get('document')->getData();
                if ($logoUpload) {
                    $document = 'expenseReportProof' . $expenseReportLine->getId() . '.' . $logoUpload[0]->guessExtension();
                    $expenseReportLine->setDocument('public/build/expenseReportLine/proof/' . $document);
                    try {
                        $logoUpload[0]->move(
                            'public/build/expenseReportLine/proof',
                            $document
                        );
                    } catch (FileException $e) {
                    }
                }
                $expenseReportLine->setExpenseReport($expenseReport);
                $entityManager->persist($expenseReportLine);
                $entityManager->flush();

                return $this->redirectToRoute('expenseReport_show', ['expenseReportID' => $expenseReport->getId()]);
            }



            return $this->render('expense_report_line/edit.html.twig', [
                'expenseReportLine' => $expenseReportLine,
                'form' => $form->createView(),
            ]);
         } else {
            return $this->redirectToRoute('account');
        }
    }

    #[Route('/delete/{expenseReportLineID}', name: 'delete')]
    #[IsGranted('ROLE_USER')]
    public function delete(EntityManagerInterface $entityManager, $expenseReportLineID): Response
    {

        $expenseReportLine = $entityManager->getRepository(ExpenseReportLine::class)->findById($expenseReportLineID)[0];
        $expenseReport = $expenseReportLine->getExpenseReport();

        $customers = $entityManager->getRepository(Customer::class)->findAll();
        $i = 0;
        while (!isset($customer) and isset($customers[$i])) {
            if ($customers[$i]->getUser() == $this->getUser()) {
                $customer = $customers[$i];;
            }
            $i++;
        }

        if (($customer == $expenseReport->getCustomer() or $this->isGranted("ROLE_TRESO")) and !$expenseReport->isComfirm()) {
            $entityManager->remove($expenseReportLine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('expenseReport_show', ['expenseReportID' => $expenseReportLine->getExpenseReport()->getId()]);
    }

    #[Route('/download/{expenseReportLine}', name: 'download')]
    #[IsGranted('ROLE_USER')]
    public function download(EntityManagerInterface $entityManager,  $expenseReportLine)
    {
        $expenseReportLine = $entityManager->getRepository(ExpenseReportLine::class)->findById($expenseReportLine)[0];
        $expenseReport = $expenseReportLine->getExpenseReport();

        $customers = $entityManager->getRepository(Customer::class)->findAll();
        $i = 0;
        while (!isset($customer) and isset($customers[$i])) {
            if ($customers[$i]->getUser() == $this->getUser()) {
                $customer = $customers[$i];;
            }
            $i++;
        }

        if ($customer == $expenseReport->getCustomer() or $this->isGranted("ROLE_TRESO")) {
            $finaleFile = $expenseReportLine->getDocument();

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($finaleFile) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($finaleFile));
            readfile($finaleFile);
        }
        return $this->redirectToRoute('expenseReport_show', ['expenseReportID' => $expenseReportLine->getExpenseReport()->getId()]);
    }
}
