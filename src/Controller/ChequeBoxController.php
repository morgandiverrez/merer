<?php

namespace App\Controller;

use App\Entity\Cheque;
use App\Form\ChequeType;
use App\Entity\ChequeBox;
use App\Form\ChequeBoxType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/chequeBox', name: 'chequeBox_')]
class ChequeBoxController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    #[IsGranted('ROLE_TRESO')]
    public function showAll(EntityManagerInterface $entityManager, Request $request): Response
    {
        $chequeBox = $entityManager->getRepository(ChequeBox::class)->findAll();
        $totals=[];
        foreach($chequeBox as $box  ){
            $totals[$box->getName()] = $entityManager->getRepository(ChequeBox::class)->montantTotale($box->getId())[0]['total_amount']; 
            if($totals[$box->getName()] == null)$totals[$box->getName()]=0;
        }
        

       
        
        return $this->render('chequeBox/showAll.html.twig', [
            'chequeBox' => $chequeBox,
            'totals'=>$totals,

        ]);
    }

    #[Route('/show/{chequeBoxID}', name: 'show')]
    #[IsGranted('ROLE_TRESO')]
    public function show(EntityManagerInterface $entityManager, $chequeBoxID): Response
    {

        $chequeBox = $entityManager->getRepository(ChequeBox::class)->findById($chequeBoxID)[0];

        $total= $entityManager->getRepository(ChequeBox::class)->montantTotale($chequeBox->getId())[0]['total_amount'];
        if ( ! $total) $total = 0;
        return $this->render('chequeBox/show.html.twig', [
            'chequeBox' => $chequeBox,
            'total'=> $total,

        ]);
    }


    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_TRESO')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $chequeBox = new ChequeBox();
        $form = $this->createForm(ChequeBoxType::class, $chequeBox);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($chequeBox);
            $entityManager->flush();
            return $this->redirectToRoute('chequeBox_show', ['chequeBoxID' => $chequeBox->getId()]);
        }

        return $this->render('chequeBox/new.html.twig', [
            'chequeBox' => $chequeBox,
            'form' => $form->createView(),


        ]);
    }

    #[Route('/edit/{chequeBoxID}', name: 'edit')]
    #[IsGranted('ROLE_TRESO')]
    public function edit(EntityManagerInterface $entityManager, Request $request, $chequeBoxID): Response
    {
        $chequeBox = $entityManager->getRepository(ChequeBox::class)->findById($chequeBoxID)[0];
        $form = $this->createForm(ChequeBoxType::class, $chequeBox);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($chequeBox);
            $entityManager->flush();
            return $this->redirectToRoute('chequeBox_show', ['chequeBoxID' => $chequeBoxID]);
        }

        return $this->render('chequeBox/edit.html.twig', [
            'chequeBox' => $chequeBox,
            'form' => $form->createView(),

        ]);
    }

   
}
