<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Badge;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\BadgeType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;



#[Route('/badge', name: 'badge_')]

class BadgeController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    public function showAll(EntityManagerInterface $entityManager): Response
    {
        $badges = $entityManager->getRepository(Badge::class)->findAll();

        return $this->render('badge/showAll.html.twig', [     
            'badges' => $badges,
        ]);
    }

    #[Route('/show/{badgeID}', name: 'show')]
    public function show(EntityManagerInterface $entityManager, $badgeID): Response
    {
        // find renvoi tjr un array (tableau), donc faut mettre [0] pour enlever l'array, si on veut plus d'une valeur s'il y en a, on met pas ou [nombre]
        $badge = $entityManager->getRepository(Badge::class)->findById($badgeID)[0];

        return $this->render('badge/show.html.twig', [
            'badge'=> $badge,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $badge = new Badge();
        $form = $this->createForm(BadgeType::class, $badge);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $logoUpload = $form->get('image')->getData();
            $name = $form->get('name')->getData();
            $nameMajuscule = strtoupper($name);

            if ($logoUpload) {
                $logoFileName = 'logo_' . $nameMajuscule . '.' . $logoUpload->guessExtension();
                $badge->setExtension($logoUpload->guessExtension());
                try {
                    $logoUpload->move(
                        'public/files/badge',
                        $logoFileName
                    );
                } catch (FileException $e) {
                }
            }



            $entityManager->persist($badge);
            $entityManager->flush();
            return $this->redirectToRoute('badge_showAll', []);
        }

        return $this->render('badge/new.html.twig', [
            'badge' => $badge,
            'form' => $form->createView(),

        ]);
    }



    #[Route('/edit/{badgeID}', name: 'edit')]
    public function edit(EntityManagerInterface $entityManager, Request $request, $badgeID): Response
    {
        $badge = $entityManager->getRepository(Badge::class)->findById($badgeID)[0];
        $form = $this->createForm(BadgeType::class, $badge);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            
            $logoUpload = $form->get('image')->getData();
            $name = $form->get('name')->getData();
            $nameMajuscule = strtoupper($name);

            if ($logoUpload) {
                $logoFileName = 'logo_' . $nameMajuscule . '.' . $logoUpload->guessExtension();
                $badge->setExtension($logoUpload->guessExtension());
                try {
                    $logoUpload->move(
                        'public/files/badge',
                        $logoFileName
                    );
                } catch (FileException $e) {
                }
            }
            $entityManager->persist($badge);
            $entityManager->flush();
            return $this->redirectToRoute('badge_showAll');
        }

        return $this->render('badge/edit.html.twig', [
            'badge' => $badge,
            'form' => $form->createView(),
            ]);
    }



    #[Route('/delete/{badgeID}', name: 'delete')]
    public function delete(EntityManagerInterface $entityManager, $badgeID): Response
    {

        $badge = $entityManager->getRepository(Badge::class)->findById($badgeID)[0];
        $entityManager->remove($badge);
        $entityManager->flush();

        return $this->redirectToRoute('badge_showAll', []);
    }
}
