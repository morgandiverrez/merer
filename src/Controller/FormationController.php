<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Formation;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\FormationType;



#[Route('/catalogue', name: 'catalogue_')]

class FormationController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    public function showAll(EntityManagerInterface $entityManager): Response
    {
        $formations = $entityManager->getRepository(Formation::class)->findAll();

        return $this->render('formation/showAll.html.twig', [     
            'formations' => $formations,
        ]);
    }

    #[Route('/show/{formationID}', name: 'show')]
    public function show(EntityManagerInterface $entityManager, $formationID): Response
    {
        // find renvoi tjr un array (tableau), donc faut mettre [0] pour enlever l'array, si on veut plus d'une valeur s'il y en a, on met pas ou [nombre]
        $formation = $entityManager->getRepository(Formation::class)->findById($formationID)[0];

        return $this->render('formation/show.html.twig', [
            'formation'=>$formation,
        ]);
    }



    #[Route('/new', name: 'new')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($formation);
            $entityManager->flush();
            return $this->redirectToRoute('catalogue_showAll', []);
            
        }

        return $this->render('formation/new.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
            
        ]);
    }

    #[Route('/edit/{formationID}', name: 'edit')]
    public function edit(EntityManagerInterface $entityManager, Request $request, $formationID): Response
    {
        $formation = $entityManager->getRepository(Formation::class)->findById($formationID)[0];
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($formation);
            $entityManager->flush();
            return $this->redirectToRoute('catalogue_showAll');
        }

        return $this->render('formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/delete/{formationID}', name: 'delete')]
    public function delete(EntityManagerInterface $entityManager, $formationID): Response
    {

        $formation = $entityManager->getRepository(Formation::class)->findById($formationID)[0];
        $entityManager->remove($formation);
        $entityManager->flush();

        return $this->redirectToRoute('catalogue_showAll', [
        ]);
    }

}
