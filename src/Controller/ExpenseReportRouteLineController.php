<?php

namespace App\Controller;

use App\Entity\ExpenseReport;
use App\Entity\ExpenseReportLine;
use App\Entity\ExpenseReportRouteLine;
use App\Form\ExpenseReportRouteLineType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


#[Route('/expenseReportRouteLine', name: 'expenseReportRouteLine_')]
class ExpenseReportRouteLineController extends AbstractController
{

    #[Route('/new/{expenseReportID}', name: 'new')]
    #[IsGranted('ROLE_TRESO')]
    public function new(EntityManagerInterface $entityManager, $expenseReportID, Request $request): Response
    {
        $expenseReportRouteLine = new ExpenseReportRouteLine();
        $form = $this->createForm(ExpenseReportRouteLineType::class, $expenseReportRouteLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $expenseReport = $entityManager->getRepository(ExpenseReport::class)->findExpenseReportById($expenseReportID);
           
            if ($expenseReportRouteLine->getDistance() <= 50) {
                $expenseReportRouteLine->setAmount($expenseReportRouteLine->getDistance() * 0.15);
            } else {
                $expenseReportRouteLine->setAmount($expenseReportRouteLine->getDistance() * 0.20);
            }

            
            $expenseReportRouteLine->setExpenseReport($expenseReport);
            $entityManager->persist($expenseReportRouteLine);
            $entityManager->flush();

            return $this->redirectToRoute('expenseReport_show', ['expenseReportID' => $expenseReport->getId()]);
        }



        return $this->render('expense_report_route_line/edit.html.twig', [
            'expenseReportRouteLine' => $expenseReportRouteLine,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/edit/{expenseReportID}/{expenseReportRouteLineID}', name: 'edit')]
    #[IsGranted('ROLE_TRESO')]
    public function edit(EntityManagerInterface $entityManager, $expenseReportID, $expenseReportRouteLineID, Request $request): Response
    {

        $expenseReportRouteLine = $entityManager->getRepository(ExpenseReportRouteLine::class)->findById($expenseReportRouteLineID)[0];
        $form = $this->createForm(ExpenseReportRouteLineType::class, $expenseReportRouteLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $expenseReport = $entityManager->getRepository(ExpenseReport::class)->findExpenseReportById($expenseReportRouteLine->getExpenseReport()->getId());

            if ($expenseReportRouteLine->getDistance() <= 50) {
                $expenseReportRouteLine->setAmount($expenseReportRouteLine->getDistance() * 0.15);
            } else {
                $expenseReportRouteLine->setAmount($expenseReportRouteLine->getDistance() * 0.20);
            }

            $expenseReportRouteLine->setExpenseReport($expenseReport);
            $entityManager->persist($expenseReportRouteLine);
            $entityManager->flush();

            return $this->redirectToRoute('expenseReport_show', ['expenseReportID' => $expenseReport->getId()]);
        }



        return $this->render('expense_report_route_line/edit.html.twig', [
            'expenseReportRouteLine' => $expenseReportRouteLine,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{expenseReportRouteLineID}', name: 'delete')]
    #[IsGranted('ROLE_TRESO')]
    public function delete(EntityManagerInterface $entityManager, $expenseReportRouteLineID): Response
    {

        $expenseReportRouteLine = $entityManager->getRepository(ExpenseReportRouteLine::class)->findById($expenseReportRouteLineID)[0];
        $entityManager->remove($expenseReportRouteLine);
        $entityManager->flush();


        return $this->redirectToRoute('expenseReport_show', ['expenseReportID' => $expenseReportRouteLine->getExpenseReport()->getId()]);
    }
}