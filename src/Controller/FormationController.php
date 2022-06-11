<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Formation;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;



#[Route('/catalogue', name: 'catalogue_')]

class FormationController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    public function showAll(EntityManager $formation): Response
    {

        $formation = $formation->getDoctrine()->getRepository(Catalogue::class)->findById($id);
       
        return $this->render('formation/showAll.html.twig', [
            'controller_name' => 'FormationController'
        ]);
    }

    #[Route('/show/{{formationID}}', name: 'show')]
    public function show(EntityManagerInterface $entityManager): Response
    {

        $entityManager = $this->$this->getDoctrine()->getRepository(Catalogue::class)->findById();

        return $this->render('formation/show.html.twig', [
            'controller_name' => 'FormationController'
        ]);
    }

    #[Route('/delete/{{formationID}}', name: 'delete')]
    public function delete(EntityManagerInterface $entityManager): Response
    {

        $entityManager = $this->$this->getDoctrine()->getRepository(Catalogue::class)->findById();

        return $this->render('formation/show.html.twig', [
            'controller_name' => 'FormationController'
        ]);
    }


    #[Route('/create}', name: 'create')]
    public function create(EntityManagerInterface $entityManager): Response
    {

        $entityManager = $this->$this->getDoctrine()->getRepository(Catalogue::class)->findById();

        return $this->render('formation/show.html.twig', [
            'controller_name' => 'FormationController'
        ]);
    }
}
