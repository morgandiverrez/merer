<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\ChequeBox;
use App\Form\CustomerType;
use App\Form\ChequeBoxType;
use App\Controller\InvoiceController;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\ExpenseReportController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/customer', name: 'customer_')]
class CustomerController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    #[IsGranted('ROLE_TRESO')]
    public function showAll(EntityManagerInterface $entityManager, Request $request): Response
    {
        $customers = $entityManager->getRepository(Customer::class)->findAll();
       

        return $this->render('customer/showAll.html.twig', [
            'customers' => $customers,

        ]);
    }

    #[Route('/show/{customerID}', name: 'show')]
    #[IsGranted('ROLE_TRESO')]
    public function show(EntityManagerInterface $entityManager, $customerID): Response
    {

        $customer = $entityManager->getRepository(Customer::class)->findById($customerID)[0];
        
        $totals = array();
        foreach ($customer->getInvoices() as $invoice) {
            array_push($totals, (new InvoiceController)->invoiceTotale($invoice));
        }

        $totalExpenseReports = array();
        foreach ($customer->getExpenseReports() as $expenseReport) {
            array_push($totalExpenseReports, (new ExpenseReportController)->expenseReportTotale($expenseReport));
        }
        
        return $this->render('customer/show.html.twig', [
            'customer' => $customer,
            'totals' => $totals,
            'totalExpenseReports' => $totalExpenseReports,

        ]);
    }


    #[Route('/showForCustomer', name: 'showForCustomer')]
    #[IsGranted('ROLE_TRESO')]
    public function showForCustomer(EntityManagerInterface $entityManager, ): Response
    {
        $user = $this->getUser();
        $customers = $entityManager->getRepository(Customer::class)->findAll();
        $i = 0 ;
        while ( ! isset($customer) and isset($customers[$i])) {
            if ($customers[$i]->getUser() == $user) {
                $customer = $customers[$i] ;
            }
            $i++;
        }

     

        $totals = array();
        foreach ($customer->getInvoices() as $invoice) {
            array_push($totals, (new InvoiceController)->invoiceTotale($invoice));
        }

        return $this->render('customer/show.html.twig', [
            'customer' => $customer,
            'totals' => $totals,

        ]);
    }


    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_TRESO')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            
           
            $entityManager->persist($customer);
            $entityManager->flush();
            return $this->redirectToRoute('customer_show', ['customerID' => $customer->getId()]);
        }
        return $this->render('customer/new.html.twig', [
            'customer' => $customer,
            'form' => $form->createView(),


        ]);
    }

    #[Route('/edit/{customerID}', name: 'edit')]
    #[IsGranted('ROLE_TRESO')]
    public function edit(EntityManagerInterface $entityManager, Request $request, $customerID): Response
    {
        $customer = $entityManager->getRepository(Customer::class)->findById($customerID)[0];
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
           
            $entityManager->persist($customer);
            $entityManager->flush();
            return $this->redirectToRoute('customer_show', ['customerID' => $customerID]);
        }

        return $this->render('customer/edit.html.twig', [
            'customer' => $customer,
            'form' => $form->createView(),

        ]);
    }
}
