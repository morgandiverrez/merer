<?php

namespace App\Controller;


use App\Entity\Cheque;
use App\Form\ChequeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/cheque', name: 'cheque_')]
class ChequeController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    #[IsGranted('ROLE_TRESO')]
    public function showAll(EntityManagerInterface $entityManager, Request $request): Response
    {
        $cheques = $entityManager->getRepository(Cheque::class)->findAllInOrder();
     
        return $this->render('cheque/showAll.html.twig', [
            'cheques' => $cheques,

        ]);
    }

   

    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_TRESO')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $cheque = new Cheque();
        $form = $this->createForm(ChequeType::class, $cheque);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            echo(1);
            echo($cheque->getChequeBox()->getName());
            $cheque->setChequeBox($cheque->getChequeBox());
            $entityManager->persist($cheque);
            $entityManager->flush();
            return $this->redirectToRoute('cheque_showAll');
        }

        return $this->render('cheque/new.html.twig', [
            'cheque' => $cheque,
            'form' => $form->createView(),


        ]);
    }

 
    #[Route('/edit/{chequeID}', name: 'edit')]
    #[IsGranted('ROLE_TRESO')]
    public function edit(EntityManagerInterface $entityManager, Request $request, $chequeID): Response
    {
        $cheque = $entityManager->getRepository(Cheque::class)->findById($chequeID)[0];
        $form = $this->createForm(ChequeType::class, $cheque);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($cheque);
            $entityManager->flush();
            return $this->redirectToRoute('cheque_showAll');
        }

        return $this->render('cheque/edit.html.twig', [
            'cheque' => $cheque,
            'form' => $form->createView(),

        ]);
    }

    #[Route('/delete/{chequeID}', name: 'delete')]
    #[IsGranted('ROLE_TRESO')]
    public function delete(EntityManagerInterface $entityManager, $chequeID): Response
    {

        $cheque = $entityManager->getRepository(cheque::class)->findById($chequeID)[0];
        $entityManager->remove($cheque);
        $entityManager->flush();

        return $this->redirectToRoute('cheque_showAll');
    }

}
