<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\EquipeEluType;
use App\Entity\EquipeElu;


 #[Route('/equipeElu', name: 'equipeElu_')]
class EquipeEluController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    public function showAll(EntityManagerInterface $entityManager): Response
    {
        $equipeElus = $entityManager->getRepository(EquipeElu::class)->findAll();

        return $this->render('equipe_elu/showAll.html.twig', [
            'equipeElus' => $equipeElus,
        ]);
    }
    
    #[Route('/show/{equipeEluID}', name: 'show')]
    public function show(EntityManagerInterface $entityManager, $equipeEluID): Response
    {
        // find renvoi tjr un array (tableau), donc faut mettre [0] pour enlever l'array, si on veut plus d'une valeur s'il y en a, on met pas ou [nombre]
        $equipeElu = $entityManager->getRepository(EquipeElu::class)->findById($equipeEluID)[0];

        return $this->render('equipe_elu/show.html.twig', [
            'equipeElu' => $equipeElu,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $equipeElu = new EquipeElu();
        $form = $this->createForm(EquipeEluType::class, $equipeElu);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($equipeElu);
            $entityManager->flush();
            return $this->redirectToRoute('equipeElu_showAll', []);
        }

        return $this->render('equipe_elu/new.html.twig', [
            'equipeElu' => $equipeElu,
            'form' => $form->createView(),

        ]);
    }



    #[Route('/edit/{equipeEluID}', name: 'edit')]
    public function edit(EntityManagerInterface $entityManager, Request $request, $equipeEluID): Response
    {
        $equipeElu = $entityManager->getRepository(EquipeElu::class)->findById($equipeEluID)[0];
        $form = $this->createForm(EquipeEluType::class, $equipeElu);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($equipeElu);
            $entityManager->flush();
            return $this->redirectToRoute('equipeElu_showAll');
        }

        return $this->render('equipe_elu/edit.html.twig', [
            'equipeElu' => $equipeElu,
            'form' => $form->createView(),
        ]);
    }



    #[Route('/delete/{equipeEluID}', name: 'delete')]
    public function delete(EntityManagerInterface $entityManager, $equipeEluID): Response
    {

        $equipeElu = $entityManager->getRepository(EquipeElu::class)->findById($equipeEluID)[0];
        $entityManager->remove($equipeElu);
        $entityManager->flush();

        return $this->redirectToRoute('equipeElu_showAll', []);
    }
}
