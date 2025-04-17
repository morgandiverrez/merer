<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[Route('/catalogue', name: 'catalogue_')]

class FormationController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    #[IsGranted('ROLE_USER')]
    public function showAll(EntityManagerInterface $entityManager): Response
    {
        $formations = $entityManager->getRepository(Formation::class)->findAll();
        $user = $this->getUser();
        return $this->render('formation/showAll.html.twig', [     
            'formations' => $formations,
            'user' => $user,
        ]);
    }

    #[Route('/show/{formationID}', name: 'show')]
    #[IsGranted('ROLE_USER')]
    public function show(EntityManagerInterface $entityManager, $formationID): Response
    {
        // find renvoi tjr un array (tableau), donc faut mettre [0] pour enlever l'array, si on veut plus d'une valeur s'il y en a, on met pas ou [nombre]
        $formation = $entityManager->getRepository(Formation::class)->findById($formationID)[0];
        $user = $this->getUser();
        return $this->render('formation/show.html.twig', [
            'formation'=>$formation,
            'user' => $user,
        ]);
    }



    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_ADMIN')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($formation);
            $entityManager->flush();
            return $this->redirectToRoute('catalogue_showAll', []);
            
        }

        return $this->render('formation/new.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/edit/{formationID}', name: 'edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(EntityManagerInterface $entityManager, Request $request, $formationID): Response
    {
        $formation = $entityManager->getRepository(Formation::class)->findById($formationID)[0];
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($formation);
            $entityManager->flush();
            return $this->redirectToRoute('catalogue_showAll');
        }

        return $this->render('formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }


    #[Route('/delete/{formationID}', name: 'delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(EntityManagerInterface $entityManager, $formationID): Response
    {

        $formation = $entityManager->getRepository(Formation::class)->findById($formationID)[0];
        $entityManager->remove($formation);
        $entityManager->flush();
        $user = $this->getUser();
        return $this->redirectToRoute('catalogue_showAll', [
        ]);
    }

}
