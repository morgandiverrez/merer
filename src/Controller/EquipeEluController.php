<?php

namespace App\Controller;

use App\Entity\EquipeElu;
use App\Form\EquipeEluType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


 #[Route('/equipeElu', name: 'equipeElu_')]

class EquipeEluController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    #[IsGranted('ROLE_FORMATEURICE')]
    public function showAll(EntityManagerInterface $entityManager): Response
    {
        $equipeElus = $entityManager->getRepository(EquipeElu::class)->findAll();
        
        return $this->render('equipe_elu/showAll.html.twig', [
            'equipeElus' => $equipeElus,
            
        ]);
    }
    
    #[Route('/show/{equipeEluID}', name: 'show')]
    #[IsGranted('ROLE_FORMATEURICE')]
    public function show(EntityManagerInterface $entityManager, $equipeEluID): Response
    {
        // find renvoi tjr un array (tableau), donc faut mettre [0] pour enlever l'array, si on veut plus d'une valeur s'il y en a, on met pas ou [nombre]
        $equipeElu = $entityManager->getRepository(EquipeElu::class)->findById($equipeEluID)[0];
        
        return $this->render('equipe_elu/show.html.twig', [
            'equipeElu' => $equipeElu,
            
        ]);
    }

    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_BF')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $equipeElu = new EquipeElu();
        $form = $this->createForm(EquipeEluType::class, $equipeElu);
        $form->handleRequest($request);
       

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($equipeElu);
            $entityManager->flush();
            return $this->redirectToRoute('equipeElu_show', ['equipeEluID' => $equipeElu -> getId()]);
        }

        return $this->render('equipe_elu/new.html.twig', [
            'equipeElu' => $equipeElu,
            'form' => $form->createView(),
            

        ]);
    }



    #[Route('/edit/{equipeEluID}', name: 'edit')]
    #[IsGranted('ROLE_BF')]
    public function edit(EntityManagerInterface $entityManager, Request $request, $equipeEluID): Response
    {
        $equipeElu = $entityManager->getRepository(EquipeElu::class)->findById($equipeEluID)[0];
        $form = $this->createForm(EquipeEluType::class, $equipeElu);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($equipeElu);
            $entityManager->flush();
            return $this->redirectToRoute('equipeElu_show', ['equipeEluID' => $equipeEluID]);
        }

        return $this->render('equipe_elu/edit.html.twig', [
            'equipeElu' => $equipeElu,
            'form' => $form->createView(),
            
        ]);
    }



    #[Route('/delete/{equipeEluID}', name: 'delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(EntityManagerInterface $entityManager, $equipeEluID): Response
    {

        $equipeElu = $entityManager->getRepository(EquipeElu::class)->findById($equipeEluID)[0];
        $entityManager->remove($equipeElu);
        $entityManager->flush();
        
        return $this->redirectToRoute('equipeElu_showAll');
    }

    #[Route('/signature/CROUS', name: 'signatureCROUS')]
    #[IsGranted('ROLE_USER')]
    public function signatureCROUS(): Response
    {
        return $this->render('equipe_elu/signature_crous.html.twig');   
    }

    #[Route('/signature/centrauxUBO', name: 'signatureCentrauxUBO')]
    #[IsGranted('ROLE_USER')]
    public function signatureCentrauxUBO(): Response
    {
        return $this->render('equipe_elu/signature_centrauxUBO.html.twig');
    }
      
}
