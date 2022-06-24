<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Retour;
use App\Entity\Seance;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RetourType;

#[Route('/retour', name: 'retour_')]

class RetourController extends AbstractController
{
    #[Route('/new/{seanceID}', name: 'new')]
    public function new(EntityManagerInterface $entityManager, Request $request, $seanceID): Response
    {
        $retour = new Retour();       
        $form = $this->createForm(RetourType::class, $retour);
        $form->handleRequest($request);
        $user = $this->getUser();


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($retour);
            $entityManager->flush();
            return $this->redirectToRoute('profil_accueil', []);
        }

        return $this->render('retour/new.html.twig', [
            'retour' => $retour,
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
