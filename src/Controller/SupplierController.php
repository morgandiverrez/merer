<?php

namespace App\Controller;

use App\Entity\Supplier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

 #[Route('/supplier', name: 'supplier_')]
class SupplierController extends AbstractController
{
   #[Route('/', name: 'showAll')]
    #[IsGranted('ROLE_TRESO')]
    public function showAll(EntityManagerInterface $entityManager, Request $request): Response
    {
        $suppliers = $entityManager->getRepository(Supplier::class)->findAll();
        
        return $this->render('supplier/showAll.html.twig', [
            'suppliers' => $suppliers,

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
