<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\FormationType;
use App\Entity\EquipeElue;


 #[Route('/equipeElue', name: 'equipeElue_')]
class EquipeElueController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    public function indshowAllex(EntityManagerInterface $entityManager): Response
    {
        $equipeElue = $entityManager->getRepository(Badge::class)->findAll();

        return $this->render('equipe_elue/showAll.html.twig', [
            'EquipeElue' => 'EquipeElueController',
        ]);
    }
    #[Route('/show/{equipeElueID}', name: 'show')]
    public function show(EntityManagerInterface $entityManager, $equipeElueID): Response
    {
        // find renvoi tjr un array (tableau), donc faut mettre [0] pour enlever l'array, si on veut plus d'une valeur s'il y en a, on met pas ou [nombre]
        $equipeElue = $entityManager->getRepository(Badge::class)->findById($equipeElueID)[0];

        return $this->render('equipeElue/show.html.twig', [
            'badge' => $equipeElue,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $equipeElue = new EquipeElue();
        $form = $this->createForm(EquipeElueType::class, $equipeElue);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($equipeElue);
            $entityManager->flush();
            return $this->redirectToRoute('equipeElue_showAll', []);
        }

        return $this->render('equipeElue/new.html.twig', [
            'equipeElue' => $equipeElue,
            'form' => $form->createView(),

        ]);
    }



    #[Route('/edit/{equipeElueID}', name: 'edit')]
    public function edit(EntityManagerInterface $entityManager, Request $request, $equipeElueID): Response
    {
        $equipeElue = $entityManager->getRepository(Badge::class)->findById($equipeElueID)[0];
        $form = $this->createForm(EquipeElueType::class, $equipeElue);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($equipeElue);
            $entityManager->flush();
            return $this->redirectToRoute('equipeElue_showAll');
        }

        return $this->render('equipe_elue/edit.html.twig', [
            'formation' => $equipeElue,
            'form' => $form->createView(),
        ]);
    }



    #[Route('/delete/{equipeElueID}', name: 'delete')]
    public function delete(EntityManagerInterface $entityManager, $equipeElueID): Response
    {

        $equipeElue = $entityManager->getRepository(EquipeElue::class)->findById($equipeElueID)[0];
        $entityManager->remove($equipeElue);
        $entityManager->flush();

        return $this->redirectToRoute('equipeElue_showAll', []);
    }
}
