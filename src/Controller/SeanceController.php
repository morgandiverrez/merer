<?php

namespace App\Controller;

use App\Entity\Lieux;
use App\Entity\Profil;
use App\Entity\Seance;
use App\Form\SeanceType;
use App\Entity\Formation;
use DateTime;
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
        $dateActuelle =new DateTime();
        
        $seances = $entityManager->getRepository(Seance::class)->findAllByDatetime($dateActuelle);
        
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

            $go = true;
            $i = 0;
            while ($go) {
                if (isset($form->get('profil')->getData()[$i])) {       
                    $nameLastNameProfil = $form->get('profil')->getData()[$i];
                    list($nameProfil, $lastNameProfil) = explode(" ", $nameLastNameProfil);
                    $profil = $entityManager->getRepository(Profil::class)->findByName(strval($nameProfil),strval($lastNameProfil))[0];
                    $profil->addSeance($seance);
                    $entityManager->persist($profil);

                    $i++;
                } else {
                    $go = false;
                }
            }

            $go = true;
            $i = 0;
            while ($go) {
                if (isset($form->get('lieux')->getData()[$i])) {
                    $nameLieux = $form->get('lieux')->getData()[$i];
                    $lieux = $entityManager->getRepository(Lieux::class)->findByName(strval($nameLieux))[0];
                    $lieux->addSeance($seance);
                    $entityManager->persist($lieux);

                    $i++;
                } else {
                    $go = false;
                }
            }

            if ($form->get('formation')->getData()) {
                $nameFormation = $form->get('formation')->getData();
                $formation = $entityManager->getRepository(Formation::class)->findByName(strval($nameFormation))[0];
                $formation->addSeance($seance);
                $entityManager->persist($formation);

                $i++;
            }

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

            $go = true;
            $i = 0;
            while ($go) {
                if (isset($form->get('profil')->getData()[$i])) {
                    $nameLastNameProfil = $form->get('profil')->getData()[$i];
                    list($nameProfil, $lastNameProfil) = explode(" ", $nameLastNameProfil);
                    $profil = $entityManager->getRepository(Profil::class)->findByName(strval($nameProfil), strval($lastNameProfil))[0];
                    $profil->addSeance($seance);
                    $entityManager->persist($profil);

                    $i++;
                } else {
                    $go = false;
                }
            }

            $go = true;
            $i = 0;
            while ($go) {
                if (isset($form->get('lieux')->getData()[$i])) {
                    $nameLieux = $form->get('lieux')->getData()[$i];
                    $lieux = $entityManager->getRepository(Lieux::class)->findByName(strval($nameLieux))[0];
                    $lieux->addSeance($seance);
                    $entityManager->persist($lieux);

                    $i++;
                } else {
                    $go = false;
                }
            }
           
            if ($form->get('formation')->getData()) {
                $nameFormation = $form->get('formation')->getData();
                $formation = $entityManager->getRepository(Formation::class)->findByName(strval($nameFormation))[0];
                $formation->addSeance($seance);
                $entityManager->persist($formation);

                $i++;
            } 
    
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
