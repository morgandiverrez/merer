<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Lieux;


#[Route('/lieux', name: 'lieux_')]

class LieuxController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    public function showAll(EntityManagerInterface $entityManager): Response
    {
        $lieux = $entityManager->getRepository(Lieux::class)->findAll();

        return $this->render('lieux/showAll.html.twig', [
            'lieux' => $lieux,
        ]);
    }

    #[Route('/show/{lieuID}', name: 'show')]
    public function show(EntityManagerInterface $entityManager, $lieuID): Response
    {
        // find renvoi tjr un array (tableau), donc faut mettre [0] pour enlever l'array, si on veut plus d'une valeur s'il y en a, on met pas ou [nombre]
        $lieu = $entityManager->getRepository(Lieux::class)->findById($lieuID)[0];

        return $this->render('lieux/show.html.twig', [
            'lieu' => $lieu,
        ]);
    }

    #[Route('/edit/{lieuID}', name: 'edit')]
    public function edit(EntityManagerInterface $entityManager, $lieuID): Response
    {

        $lieu = $entityManager->getRepository(Lieux::class)->findById($lieuID)[0];

        return $this->render('lieux/edit.html.twig', [
            'lieu' => $lieu,
        ]);
    }

    #[Route('/delete/{lieuID}', name: 'delete')]
    public function delete(EntityManagerInterface $entityManager, $lieuID): Response
    {

        $lieu = $entityManager->getRepository(Lieux::class)->findById($lieuID)[0];
        $entityManager->remove($lieu);
        $entityManager->flush();

        return $this->redirectToRoute('lieux_showAll', []);
    }

}
