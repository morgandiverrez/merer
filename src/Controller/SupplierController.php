<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Supplier;
use App\Form\CustomerType;
use App\Form\SupplierType;
use App\Controller\InvoiceController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('/show/{supplierID}', name: 'show')]
    #[IsGranted('ROLE_TRESO')]
    public function show(EntityManagerInterface $entityManager, $supplierID): Response
    {

        $supplier = $entityManager->getRepository(Supplier::class)->findById($supplierID)[0];
        
        
        
        return $this->render('supplier/show.html.twig', [
            'supplier' => $supplier,

        ]);
    }


    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_TRESO')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $supplier = new Supplier();
        $form = $this->createForm(SupplierType::class, $supplier);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($supplier);
            $entityManager->flush();
            return $this->redirectToRoute('supplier_show', ['supplierID' => $supplier->getId()]);
        }

        return $this->render('supplier/new.html.twig', [
            'supplier' => $supplier,
            'form' => $form->createView(),


        ]);
    }

    #[Route('/edit/{supplierID}', name: 'edit')]
    #[IsGranted('ROLE_TRESO')]
    public function edit(EntityManagerInterface $entityManager, Request $request, $supplierID): Response
    {
        $supplier = $entityManager->getRepository(Supplier::class)->findById($supplierID)[0];
        $form = $this->createForm(SupplierType::class, $supplier);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($supplier);
            $entityManager->flush();
            return $this->redirectToRoute('supplier_show', ['supplierID' => $supplierID]);
        }

        return $this->render('supplier/edit.html.twig', [
            'supplier' => $supplier,
            'form' => $form->createView(),

        ]);
    }
}
