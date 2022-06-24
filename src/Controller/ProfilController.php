<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Entity\User;
use App\Form\ProfilType;
use App\Entity\EquipeElu;
use App\Entity\Association;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/profil', name: 'profil_')]
class ProfilController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    #[IsGranted('ROLE_ADMIN')]
    public function showAll(EntityManagerInterface $entityManager): Response
    {
        $profils = $entityManager->getRepository(Profil::class)->findAll();
        $user = $this->getUser();

        return $this->render('profil/showAll.html.twig', [
            'profils' => $profils,
            'user' => $user,
        ]);
    }

    #[Route('/show/{profilID}', name: 'show')]
    public function show(EntityManagerInterface $entityManager, $profilID): Response
    {
        // find renvoi tjr un array (tableau), donc faut mettre [0] pour enlever l'array, si on veut plus d'une valeur s'il y en a, on met pas ou [nombre]
        $profil = $entityManager->getRepository(Profil::class)->findById($profilID)[0];
         $user = $this->getUser();

        return $this->render('profil/show.html.twig', [
            'profil' => $profil,
            'user' => $user,
        ]);
    }

    #[Route('/edit', name: 'edit')]
    #[IsGranted('ROLE_USER')]
    public function edit(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();

        $profil = $entityManager->getRepository(Profil::class)->findByUser($user);
        $form = $this->createForm(ProfilType::class, $profil);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $profil->setLastName(mb_strtoupper($profil->getLastName()));
            $profil->setName(mb_convert_case($profil->getName(), MB_CASE_TITLE, "UTF-8"));
            $go = true;
            $i = 0;

            while ($go) {
                if (isset($form->get('equipeElu')->getData()[$i])) {
                    $nameEquipeElu = $form->get('equipeElu')->getData()[$i];
                    $equipeElu = $entityManager->getRepository(EquipeElu::class)->findByName(strval($nameEquipeElu))[0];  
                    $profil->addEquipeElu($equipeElu);
                    $entityManager->persist($profil);
                    $entityManager->flush();
                    $i++;
                } else {
                    $go = false;
                }
            }
            
            $go = true;
            $i = 0;
            while ($go) {
                if (isset($form->get('association')->getData()[$i])) {
                    $nameAssociation = $form->get('association')->getData()[$i];
                    $association = $entityManager->getRepository(Association::class)->findBySigle(strval($nameAssociation))[0];
                    $profil->addAssociation($association);
                    $i++;
                } else {
                    $go = false;
                }
            }

            $entityManager->persist($profil);
            $entityManager->flush();

           return $this->redirectToRoute('profil_show',['profilID' => $profil->getID()]);
        }

        return $this->render('profil/edit.html.twig', [
            'profil' => $profil,
            'form' => $form->createView(),
            'user'=> $user,
        ]);
    }


    #[Route('/edit/{profilID}', name: 'edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function editByID(EntityManagerInterface $entityManager, Request $request, $profilID): Response
    {
        $user = $this->getUser();
        $profil = $entityManager->getRepository(Profil::class)->findByID($profilID);
        $form = $this->createForm(ProfilType::class, $profil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $profil->setLastName(mb_strtoupper($profil->getLastName()));
            $profil->setName(mb_convert_case($profil->getName(), MB_CASE_TITLE, "UTF-8"));
            $go = true;
            $i = 0;

            while ($go) {
                if (isset($form->get('equipeElu')->getData()[$i])) {
                    $nameEquipeElu = $form->get('equipeElu')->getData()[$i];
                    $equipeElu = $entityManager->getRepository(EquipeElu::class)->findByName(strval($nameEquipeElu))[0];
                    $profil->addEquipeElu($equipeElu);
                    $entityManager->persist($profil);
                    $entityManager->flush();
                    $i++;
                } else {
                    $go = false;
                }
            }

            $go = true;
            $i = 0;
            while ($go) {
                if (isset($form->get('association')->getData()[$i])) {
                    $nameAssociation = $form->get('association')->getData()[$i];
                    $association = $entityManager->getRepository(Association::class)->findBySigle(strval($nameAssociation))[0];
                    $profil->addAssociation($association);
                    $i++;
                } else {
                    $go = false;
                }
            }

            $entityManager->persist($profil);
            $entityManager->flush();

            return $this->redirectToRoute('profil_show', ['profilID' => $profil->getID()]);
        }

        return $this->render('profil/edit.html.twig', [
            'profil' => $profil,
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_USER')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $profil = new Profil();
        $form = $this->createForm(ProfilType::class, $profil);
        $form->handleRequest($request);
        $user = $this->getUser();


        if ($form->isSubmitted() && $form->isValid()) {

            $profil->setLastName(mb_strtoupper($profil->getLastName()));
            $profil->setName(mb_convert_case($profil->getName(), MB_CASE_TITLE, "UTF-8"));
           
            $go=true; $i=0;

            while ($go) {
                if (isset($form->get('equipeElu')->getData()[$i])) {
                    $nameEquipeElu = $form->get('equipeElu')->getData()[$i];
                    $equipeElu = $entityManager->getRepository(EquipeElu::class)->findByName(strval($nameEquipeElu))[0];
                    $profil->addEquipeElu($equipeElu);
                    $i++;
                } else {
                    $go = false;
                }
            }

            $go = true; $i = 0;
            while ($go) {
                if (isset($form->get('association')->getData()[$i])) {
                    $sigleAssociation = $form->get('association')->getData()[$i];
                    $association = $entityManager->getRepository(Association::class)->findBySigle(strval($sigleAssociation))[0];
                    $profil->addAssociation($association);
                    $i++;
                } else {
                    $go = false;
                }
            }

            $entityManager->persist($profil);
            $entityManager->flush();

           
            return $this->redirectToRoute('profil_show', ['profilID' => $profil->getID()]);
        }

        return $this->render('profil/edit.html.twig', [
            'profil' => $profil,
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
    
}