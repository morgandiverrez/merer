<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Badge;
use App\Entity\Profil;
use App\Form\UserType;
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
    public function showAll(EntityManagerInterface $entityManager, Request $request): Response
    {
        $profils = $entityManager->getRepository(Profil::class)->findAll();

        if ($request->isMethod('post')) {
            $posts = $request->request->all();

            if ($posts['name']) {
                $profils = array_intersect($profils, $entityManager->getRepository(Profil::class)->findAllByName($posts['name']));
            }
            if ($posts['last_name']) {
                $profils = array_intersect($profils, $entityManager->getRepository(Profil::class)->findAllByLastName($posts['last_name']));
            }
            if ($posts['scoreMax']) {
                $profils = array_intersect($profils, $entityManager->getRepository(Profil::class)->findAllScoreInferior($posts['scoreMax']));
            }
            if ($posts['scoreMin']) {
                $profils = array_intersect($profils, $entityManager->getRepository(Profil::class)->findAllScoreSuperior($posts['scoreMin']));
            }
            if ($posts['DOBmin']) {
                $profils = array_intersect($profils, $entityManager->getRepository(Profil::class)->findAllDobSuperior($posts['DOBmin']));
            }
            if ($posts['DOBmax']) {
                $profils = array_intersect($profils, $entityManager->getRepository(Profil::class)->findAllDobInferior($posts['DOBmax']));
            }
            if ($posts['badge']) {
                $badges = $entityManager->getRepository(Badge::class)->findAllByName($posts['badge']);
                $badgeProfils = array();
                foreach ($badges as $badge) {
                    foreach ($badge->getProfil() as $profil) {
                        array_push($badgeProfils, $profil);
                    }
                }
                $profils = array_intersect($profils, $badgeProfils);
            }
            if ($posts['association']) {
                $associations = $entityManager->getRepository(Association::class)->findAllByName($posts['association']);
                $associationProfils = array();
                foreach ($associations as $association) {
                    foreach ($association->getProfil() as $profil) {
                        array_push($associationProfils, $profil);
                    }
                }
                $profils = array_intersect($profils, $associationProfils);
            }
            if ($posts['equipeElu']) {
                $equipeElus = $entityManager->getRepository(EquipeElu::class)->findAllByName($posts['equipeElu']);
                $equipeEluProfils = array();
                foreach ($equipeElus as $equipeElu) {
                    foreach ($equipeElu->getProfil() as $profil) {
                        array_push($equipeEluProfils, $profil);
                    }
                }
                $profils = array_intersect($profils, $equipeEluProfils);
            }
        }

        return $this->render('profil/showAll.html.twig', [
            'profils' => $profils,
            
        ]);
    }

    #[Route('/', name: 'show')] // afficheur profil perso pour user
    #[IsGranted('ROLE_USER')] 
    public function show(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $profils = $entityManager->getRepository(Profil::class)->findAll();

        $profilExistePas = false;
        foreach ($profils as $testProfil) {
            if ($testProfil->getUser() == $user) {
                $profilExistePas = true;
            }
        }

        if( ! $profilExistePas) {
            $profil = new Profil;
            $entityManager->persist($profil);
            $profil->setUser($user);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('profil_edit', ['profilID' => $profil->getID()]);
        }
        
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
    #[IsGranted('ROLE_FORMA')]
    public function delete(EntityManagerInterface $entityManager, $profilID): Response
    {

        $profil = $entityManager->getRepository(Profil::class)->findById($profilID)[0];
        $entityManager->remove($profil);
        $entityManager->flush();


        return $this->redirectToRoute('profil_showAll', []);
    }


    #[Route('/show/seances/{profilID}', name: 'seances')]
    #[IsGranted('ROLE_USER')]
    public function listeFormationsSuivies(EntityManagerInterface $entityManager, $profilID): Response
    {
        
        $profil = $entityManager->getRepository(Profil::class)->findById($profilID)[0];
        $inscriptions = $profil-> getSeanceProfil();


        return $this->render('profil/listeFormationSuivie.html.twig', [
            'profil' => $profil,
            'inscriptions' => $inscriptions,
        ]);
    }


  
    #[Route('/showFormateurice', name: 'formateurice')]
    #[IsGranted('ROLE_BF')]
    public function formateurice(EntityManagerInterface $entityManager): Response
    {

        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('profil/ListeFormateurice.html.twig', [
            'users' => $users,
            
        ]);
    }

    #[Route('/roles', name: 'roles')]
    #[IsGranted('ROLE_ADMIN')]
    public function roles(EntityManagerInterface $entityManager, Request $request): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();


        if ($request->isMethod('post')) {
            $posts = $request->request->all();

            foreach( $users as $user){
                if($user->getEmail() == "presidence@fedeb.net" or $user->getEmail() == "formation@fedeb.net" ){
                    $user->setRoles(["ROLE_ADMIN"]);  
                }
                elseif(isset($posts[$user->getId() . '_bf'])){
                        $user->setRoles(["ROLE_BF"]);
                }       
                elseif(isset($posts[$user->getId() . '_formateurice'])){
                                $user->setRoles(["ROLE_FORMATEURICE"]);           
                }
                else{ 
                    $user->setRoles([]);
                } 
            }   
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('profil_showAll');
        }


        return $this->render('profil/gestionRoles.html.twig', [
            'users' => $users,  

        ]);
    }

    
}