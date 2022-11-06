<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BPController extends AbstractController
{
    #[Route('/b/p', name: 'app_b_p')]
    public function index(): Response
    {
        return $this->render('bp/index.html.twig', [
            'controller_name' => 'BPController',
        ]);
    }
}
