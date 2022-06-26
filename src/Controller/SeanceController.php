<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Form\SeanceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[Route('/seance', name: 'seance_')]

class SeanceController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    #[IsGranted('ROLE_USER')]
    public function showAll(EntityManagerInterface $entityManager): Response
    {
        $seances = $entityManager->getRepository(Seance::class)->findAllByDatetime();

        return $this->render('seance/showAll.html.twig', [
            'seances' => $seances,
            
        ]);
    }

    #[Route('/showAllForFormateurice', name: 'showAllForFormateurice')]
    #[IsGranted('ROLE_FORMATEURICE')]
    public function showAllForFormateurice(EntityManagerInterface $entityManager): Response
    {
        $seances = $entityManager->getRepository(Seance::class)->findAll();

        return $this->render('seance/showAllForFormateurice.html.twig', [
            'seances' => $seances,

        ]);
    }


    #[Route('/showForFormateurice/{seanceID}', name: 'showForFormateurice')]
    #[IsGranted('ROLE_FORMATEURICE')]
    public function show(EntityManagerInterface $entityManager, $seanceID): Response
    {
        $seance = $entityManager->getRepository(Seance::class)->findByID($seanceID)[0];

        return $this->render('seance/show.html.twig', [
            'seance' => $seance,
        ]);
    }

    #[Route('/show/{seanceID}', name: 'show')]
    #[IsGranted('ROLE_USER')]
    public function showForUser(EntityManagerInterface $entityManager, $seanceID): Response
    {
        $seance = $entityManager->getRepository(Seance::class)->findByID($seanceID)[0];

        return $this->render('seance/showForUser.html.twig', [
            'seance' => $seance,
        ]);
    }

    #[Route('/liste_inscrit/{seanceID}', name: 'liste_inscrit')]
    #[IsGranted('ROLE_FORMATEURICE')]
    public function menu(EntityManagerInterface $entityManager, $seanceID): Response
    {
        $seance = $entityManager->getRepository(Seance::class)->findByID($seanceID)[0];

        return $this->render('seance/listeInscrit.html.twig', [
            'seance' => $seance,
        ]);
    }

    #[Route('/edit/{seanceID}', name: 'edit')]
    #[IsGranted('ROLE_BF')]
    public function edit(EntityManagerInterface $entityManager, Request $request, $seanceID): Response
    {
        $seance = $entityManager->getRepository(Seance::class)->findById($seanceID)[0];
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($seance);
            $entityManager->flush();
            return $this->redirectToRoute('seance_showForFormateurice', ['seanceID' => $seance->getID()]);
        }

        return $this->render('seance/edit.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_BF')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $seance = new Seance();
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($seance);
            $entityManager->flush();
            return $this->redirectToRoute('seance_showForFormateurice', ['seanceID' => $seance->getID()]);
        }

        return $this->render('seance/new.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
            
        ]);
    }
}
