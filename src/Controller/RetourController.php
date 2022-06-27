<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Entity\Retour;
use App\Entity\Seance;
use App\Form\RetourType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/retour', name: 'retour_')]

class RetourController extends AbstractController
{
    #[Route('/new/{seanceID}', name: 'new')]
    public function new(EntityManagerInterface $entityManager, Request $request, $seanceID): Response
    {
        $retour = new Retour();

        $seance = $entityManager->getRepository(Seance::class)->findByID(strval($seanceID))[0];
        $retour->setSeance($seance);

        $user = $this->getUser();
        $profils = $entityManager->getRepository(Profil::class)->findAll();
        foreach ($profils as $testProfil) {
            if ($testProfil->getUser() == $user) {
                $profil = $testProfil;
            }
        }
        $retour->setProfil($profil);
        
        $form = $this->createForm(RetourType::class, $retour);
        $form->handleRequest($request);
       

        if ($form->isSubmitted() && $form->isValid()) {

            

            $entityManager->persist($retour);
            $entityManager->flush();
            return $this->redirectToRoute('profil_accueil', []);
        }

        return $this->render('retour/new.html.twig', [
            'retour' => $retour,
            'form' => $form->createView(),
           
        ]);
    }
}
