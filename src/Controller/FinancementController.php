<?php

namespace App\Controller;

use App\Entity\Financement;
use App\Form\FinancementType;
use App\Entity\TransactionLine;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/financement', name: 'financement_')]
class FinancementController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    #[IsGranted('ROLE_TRESO')]
    public function showAll(EntityManagerInterface $entityManager, ): Response
    {
        $financements = $entityManager->getRepository(Financement::class)->findAllInOrder();

       
        return $this->render('financement/showAll.html.twig', [
            'financements' => $financements,

        ]);
    }

    #[Route('/show/{financementID}', name: 'show')]
    #[IsGranted('ROLE_TRESO')]
    public function show(EntityManagerInterface $entityManager, $financementID): Response
    {
        $financement = $entityManager->getRepository(Financement::class)->findFinancementById($financementID);
        $total = [];
        foreach($financement->getFinancementLines() as $financementLine){
            $total[$financementLine->getId()] =$entityManager->getRepository(TransactionLine::class)->totalByFinancementLine($financementLine)['total'];
        }
        return $this->render('financement/show.html.twig', [
            'financement' => $financement,
            'total' => $total,

        ]);
    }

    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_TRESO')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $financement = new Financement();
        $form = $this->createForm(FinancementType::class, $financement);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($financement->getFinancementLines() as $financementLine) {
                $entityManager->persist($financementLine);
            }
            $entityManager->persist($financement);
            $entityManager->flush();
            return $this->redirectToRoute('financement_show', ['financementID' => $financement->getId()]);
        }

        return $this->render('financement/new.html.twig', [
            'financement' => $financement,
            'form' => $form->createView(),


        ]);
    }



    #[Route('/edit/{financementID}', name: 'edit')]
    #[IsGranted('ROLE_TRESO')]
    public function edit(EntityManagerInterface $entityManager, Request $request, $financementID): Response
    {
        $financement = $entityManager->getRepository(Financement::class)->findFinancementById($financementID);
        $form = $this->createForm(FinancementType::class, $financement);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($financement->getFinancementLines() as $financementLine) {
                $entityManager->persist($financementLine);
            }
            $entityManager->persist($financement);
            $entityManager->flush();
            return $this->redirectToRoute('financement_show', ['financementID' => $financementID]);
        }

        return $this->render('financement/edit.html.twig', [
            'financement' => $financement,
            'form' => $form->createView(),

        ]);
    }

    #[Route('/delete/{financementID}', name: 'delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(EntityManagerInterface $entityManager, $financementID): Response
    {

        $financement = $entityManager->getRepository(Financement::class)->findFinancementById($financementID);
        $entityManager->remove($financement);
        $entityManager->flush();

        return $this->redirectToRoute('financement_showAll');
    }

 
}
