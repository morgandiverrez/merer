<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ProfilType;
use App\Entity\Profil;
use Symfony\Component\HttpFoundation\Request;

#[Route('/profil', name: 'profil_')]

class ProfilController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function accueil(EntityManagerInterface $entityManager): Response
    {
        return $this->render('profil/accueil.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }

    #[Route('/edit', name: 'edit')]
    public function edit(EntityManagerInterface $entityManager, Request $request, $profilID): Response
    {
        $profil = $entityManager->getRepository(Profil::class)->findById($profilID)[0];
        $form = $this->createForm(ProfilType::class, $profil);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($profil);
            $entityManager->flush();
            return $this->redirectToRoute('profil_accueil');
        }

        return $this->render('profil/edit.html.twig', [
            'profil' => $profil,
            'form' => $form->createView(),
        ]);
    }
    
}
