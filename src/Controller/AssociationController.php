<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Profil;
use App\Entity\Association;
use App\Form\AssociationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


#[Route('/association', name: 'association_')]

class AssociationController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    #[IsGranted('ROLE_ADMIN')]
    public function showAll(EntityManagerInterface $entityManager): Response
    {
        $associations = $entityManager->getRepository(Association::class)->findAll();
        $users = $entityManager->getRepository(User::class)->findAll();
        $user = $this->getUser();


        return $this->render('association/showAll.html.twig', [
            'associations' => $associations,
            'user'=> $user,
            'users'=> $users,
        ]);
    }

    #[Route('/show/{associationID}', name: 'show')]
    #[IsGranted('ROLE_ADMIN')]
    public function show(EntityManagerInterface $entityManager, $associationID): Response
    {
        // find renvoi tjr un array (tableau), donc faut mettre [0] pour enlever l'array, si on veut plus d'une valeur s'il y en a, on met pas ou [nombre]
        $association = $entityManager->getRepository(Association::class)->findById($associationID)[0];
        $user = $this->getUser();
        return $this->render('association/show.html.twig', [
            'association' => $association,
            'user' => $user,     
        ]);
    }

    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_ADMIN')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $association = new Association();
        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);
        
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

             $logoUpload=$form->get('image')->getData();
             $sigle = $form->get('sigle')->getData();
             $sigleMajuscule = strtoupper($sigle);

            if($logoUpload){
                $logoFileSigle = 'logo_'. $sigleMajuscule . '.' . $logoUpload->guessExtension();
                $association->setImage('public/build/association/' . $logoFileSigle);
                try {
                    $logoUpload->move(
                        'public/build/association',
                        $logoFileSigle
                    );
                } catch (FileException $e) {
                    
                }              
            }

            $entityManager->persist($association);
            $entityManager->flush();
            return $this->redirectToRoute('association_showAll', []);
        }

        return $this->render('association/new.html.twig', [
            'association' => $association,
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }



    #[Route('/edit/{associationID}', name: 'edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(EntityManagerInterface $entityManager, Request $request, $associationID): Response
    {
        $association = $entityManager->getRepository(Association::class)->findById($associationID)[0];
        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

            $logoUpload = $form->get('image')->getData();
            $sigle = $form->get('sigle')->getData();
            $sigleMajuscule = strtoupper($sigle);
            
            if ($logoUpload) {
                $logoFileSigle = 'logo_' . $sigleMajuscule . '.' . $logoUpload->guessExtension();
                $association->setImage('build/association/' . $logoFileSigle);
                try {
                    $logoUpload->move(
                        'build/association',
                        $logoFileSigle
                    );
                } catch (FileException $e) {
                }
            }

            $entityManager->persist($association);
            $entityManager->flush();
            return $this->redirectToRoute('association_showAll');
        }

        return $this->render('association/edit.html.twig', [
            'association' => $association,
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }



    #[Route('/delete/{associationID}', name: 'delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(EntityManagerInterface $entityManager, $associationID): Response
    {

        $association = $entityManager->getRepository(Association::class)->findById($associationID)[0];
        $entityManager->remove($association);
        $entityManager->flush();

        $user = $this->getUser();

        return $this->redirectToRoute('association_showAll', []);
    }
}