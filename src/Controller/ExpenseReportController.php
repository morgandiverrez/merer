<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExpenseReportController extends AbstractController
{
    #[Route('/expense/report', name: 'app_expense_report')]
    public function index(): Response
    {
        return $this->render('expense_report/index.html.twig', [
            'controller_name' => 'ExpenseReportController',
        ]);
    }
}
