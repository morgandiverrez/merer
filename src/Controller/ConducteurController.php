<?php

namespace App\Controller;

use App\Entity\Conducteur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/conducteur', name: 'conducteur_')]
class ConducteurController extends AbstractController
{
    #[Route('/edit/{seanceID}', name: 'edit')]
    public function edit(EntityManagerInterface $entityManager, $seanceID, Request $request): Response
    {
        $seance = $entityManager->getRepository(Seance::class)->findby($seanceID);
        $dateDebutModuleSuivant = $seance->getDatetime();
        if ($request->isMethod('post')) {
            $posts = $request->request->all();

            $newModule = new Conducteur();
            $newModule->setSeance($seance);
            if ($posts['duree']) {
                $newModule->setDuration($posts['duree']);
                $dateDebutModuleSuivant -= $posts['durée'];
            }

            if ($posts['name']) {
                $newModule = $posts['name'];
            }
            if ($posts['objectif']) {
                $newModule = $posts['objectif'];
            }
            if ($posts['activite']) {
                $newModule = $posts['activite'];
            }
            if ($posts['contenu']) {
                $newModule = $posts['contenu'];
            }
            if ($posts['logistique']) {
                $newModule = $posts['logistique'];
            }
            if ($posts['observation']) {
                $newModule = $posts['contenu'];
            }

            $entityManager->persist($newModule);
            $entityManager->flush();
            
        }
        return $this->render('equipe_elu/showAll.html.twig', [
            'seance' => $seance,
            'activite' => $activite,
            debutModule''=>$dateDebutModuleSuivant,
            
        ]);
    }
}
