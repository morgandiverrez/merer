<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Formation;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/catalogue', name: 'catalogue_')]

class FormationController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    public function showAll(EntityManagerInterface $entityManager): Response
    {
    
        $entityManager = $this->$this->getDoctrine()->getRepository(Catalogue::class)->findById();
       
        return $this->render('formation/showAll.html.twig', [
            'controller_name' => 'FormationController'
        ]);
    }
}
