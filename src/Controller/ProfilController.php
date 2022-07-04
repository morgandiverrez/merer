<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Form\ProfilType;
use App\Entity\EquipeElu;
use App\Entity\Association;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/account', name: 'profil_')]
class ProfilController extends AbstractController
{
    #[Route('/showAll', name: 'showAll')]
    #[IsGranted('ROLE_FORMATEURICE')]
    public function showAll(EntityManagerInterface $entityManager): Response
    {
        $profils = $entityManager->getRepository(Profil::class)->findAll();
       

        return $this->render('profil/showAll.html.twig', [
            'profils' => $profils,
            
        ]);
    }

    #[Route('/', name: 'show')] // afficheur profil perso pour user
    #[IsGranted('ROLE_USER')] 
    public function show(): Response
    {
        
        return $this->render('profil/show.html.twig', [           
        ]);
    }


    #[Route('/show/{profilID}', name: 'showForFormateurice')]
    #[IsGranted('ROLE_FORMATEURICE')] 
    public function showForFormateurice(EntityManagerInterface $entityManager, $profilID): Response
    {
        
        $profil = $entityManager->getRepository( Profil::class)->findByID($profilID)[0];
        return $this->render('profil/showForFormateurice.html.twig', [
            'profil' => $profil,
        ]);
    }

   


    #[Route('/edit/{profilID}', name: 'editByAdmin')]
    #[IsGranted('ROLE_BF')]
    public function editByAdmin(EntityManagerInterface $entityManager, Request $request, $profilID): Response
    {
        $user = $this->getUser();
        $profil = $entityManager->getRepository(Profil::class)->findByID($profilID)[0];
        $form = $this->createForm(ProfilType::class, $profil);

        $equipeElus = $profil->getEquipeElu();
        foreach ($equipeElus as $equipe) {
            $profil->removeEquipeElu($equipe);
        }
        $associations = $profil->getEquipeElu();
        foreach ($associations as $asso) {
            $asso->removeProfil($profil);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $profil->setLastName(mb_strtoupper($profil->getLastName()));
            $profil->setName(mb_convert_case($profil->getName(), MB_CASE_TITLE, "UTF-8"));

            $go = true; $i = 0;
            while ($go) {
                if (isset($form->get('equipeElu')->getData()[$i])) {
                    $nameEquipeElu = $form->get('equipeElu')->getData()[$i];
                    $equipeElu = $entityManager->getRepository(EquipeElu::class)->findByName(strval($nameEquipeElu))[0];
                    $equipeElu->addProfil($profil);
                    $entityManager->persist($profil);
                    
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
                    $association->addProfil($profil);
                    $entityManager->persist($profil);
                    $i++;
                } else {
                    $go = false;
                }
            }

            $entityManager->persist($profil);
            $entityManager->flush();

            return $this->redirectToRoute('profil_showForFormateurice', ['profilID' => $profil->getID()]);
        }

        return $this->render('profil/edit.html.twig', [
            'profil' => $profil,
            'form' => $form->createView(),
            
        ]);
    }



    #[Route('/edit', name: 'edit')]
    #[IsGranted('ROLE_USER')]
    public function edit(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();
        $profils = $entityManager-> getRepository(Profil::class)->findAll();
        foreach ($profils as $testProfil ){
            if ($testProfil->getUser() == $user){
                $profil = $testProfil;
            }
        }

        $form = $this->createForm(ProfilType::class, $profil);

        $equipeElus = $profil->getEquipeElu();
        foreach ($equipeElus as $equipe) {
            $profil->removeEquipeElu($equipe);
        }
        $associations = $profil->getEquipeElu();
        foreach ($associations as $asso) {
            $asso->removeProfil($profil);
        }
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $profil->setLastName(mb_strtoupper($profil->getLastName()));
            $profil->setName(mb_convert_case($profil->getName(), MB_CASE_TITLE, "UTF-8"));

            $go = true; $i = 0;
            while ($go) {
                if (isset($form->get('equipeElu')->getData()[$i])) {
                    $nameEquipeElu = $form->get('equipeElu')->getData()[$i];
                    $equipeElu = $entityManager->getRepository(EquipeElu::class)->findByName(strval($nameEquipeElu))[0];
                    $equipeElu->addProfil($profil);
                    $entityManager->persist($profil);

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
                    $association->addProfil($profil);
                    $entityManager->persist($profil);
                    $i++;
                } else {
                    $go = false;
                }
            }

            $entityManager->persist($profil);
            $entityManager->flush();

            return $this->redirectToRoute('profil_show');
        }

        return $this->render('profil/edit.html.twig', [
            'form' => $form->createView(),
            'profil' => $profil,
        ]);
    }

    #[Route('/delete/{profilID}', name: 'delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(EntityManagerInterface $entityManager, $profilID): Response
    {

        $profil = $entityManager->getRepository(Profil::class)->findById($profilID)[0];
        $entityManager->remove($profil);
        $entityManager->flush();


        return $this->redirectToRoute('profil_showAll', []);
    }

    
}