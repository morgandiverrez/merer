<?php

namespace App\Controller;

use App\Entity\Badge;
use App\Form\BadgeType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;



#[Route('/badge', name: 'badge_')]

class BadgeController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    #[IsGranted('ROLE_USER')]
    public function showAll(EntityManagerInterface $entityManager): Response
    {
        $badges = $entityManager->getRepository(Badge::class)->findAll();
        $user = $this->getUser();
        return $this->render('badge/showAll.html.twig', [     
            'badges' => $badges,
            'user' => $user,
        ]);
    }

    #[Route('/show/{badgeID}', name: 'show')]
    #[IsGranted('ROLE_USER')]
    public function show(EntityManagerInterface $entityManager, $badgeID): Response
    {
        // find renvoi tjr un array (tableau), donc faut mettre [0] pour enlever l'array, si on veut plus d'une valeur s'il y en a, on met pas ou [nombre]
        $badge = $entityManager->getRepository(Badge::class)->findById($badgeID)[0];
        $user = $this->getUser();
        return $this->render('badge/show.html.twig', [
            'badge'=> $badge,
            'user' => $user,
        ]);
    }

    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_BF')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $badge = new Badge();
        $form = $this->createForm(BadgeType::class, $badge);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

            $logoUpload = $form->get('image')->getData();
            $name = $form->get('name')->getData();
            $nameMajuscule = strtoupper($name);

            if ($logoUpload) {
                $logoFileName = 'badge_' . $nameMajuscule . '.' . $logoUpload->guessExtension();
                $badge->setImage('build/badge/'.$logoFileName);
                try {
                    $logoUpload->move(
                        'build/badge',
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
            'user' => $user,

        ]);
    }



    #[Route('/edit/{badgeID}', name: 'edit')]
    #[IsGranted('ROLE_BF')]
    public function edit(EntityManagerInterface $entityManager, Request $request, $badgeID): Response
    {
        $badge = $entityManager->getRepository(Badge::class)->findById($badgeID)[0];
        $form = $this->createForm(BadgeType::class, $badge);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            
            $logoUpload = $form->get('image')->getData();
            $name = $form->get('name')->getData();
            $nameMajuscule = strtoupper($name);

            if ($logoUpload) {
                $logoFileName = 'logo_' . $nameMajuscule . '.' . $logoUpload->guessExtension();
                $badge->setImage('public/build/badge/' . $logoFileName);
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
            'user' => $user,
            ]);
    }



    #[Route('/delete/{badgeID}', name: 'delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(EntityManagerInterface $entityManager, $badgeID): Response
    {

        $badge = $entityManager->getRepository(Badge::class)->findById($badgeID)[0];
        $entityManager->remove($badge);
        $entityManager->flush();
        $user = $this->getUser();
        return $this->redirectToRoute('badge_showAll', []);
    }
}
