<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Seance;
use App\Form\SeanceType;
use Doctrine\ORM\EntityManagerInterface;



#[Route('/seance', name: 'seance_')]

class SeanceController extends AbstractController
{
    #[Route('/', name: 'menu')]
    public function menu(EntityManagerInterface $entityManager): Response
    {
        return $this->render('seance/menu.html.twig', [
            'controller_name' => 'SeanceController',
        ]);
    }
}
