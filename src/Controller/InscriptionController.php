<?php

namespace App\Controller;

use DateTime;
use App\Entity\Profil;
use App\Entity\Seance;
use App\Entity\SeanceProfil;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InscriptionController extends AbstractController
{
    #[Route('/inscription/{seanceID}', name: 'inscription')]
    #[IsGranted('ROLE_USER')]
    public function index(EntityManagerInterface $entityManager, Request $request, $seanceID): Response
    {
        $seanceProfil = new SeanceProfil();
        $seance = $entityManager->getRepository(Seance::class)->findByID(strval($seanceID))[0];
        $form = $this->createForm(InscriptionType::class, $seanceProfil,[ 'liste_lieu' => $seance->getLieux()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $profils = $entityManager->getRepository(Profil::class)->findAll();
            foreach ($profils as $testProfil) {
                if ($testProfil->getUser() == $user) {
                    $profil = $testProfil;
                }
            $seanceProfil->setHorrodateur(new DateTime());
            }
            $profil->addSeanceProfil($seanceProfil);

            $seance = $entityManager->getRepository(Seance::class)->findByID(strval($seanceID))[0];
            $seance->addSeanceProfil($seanceProfil);

            $entityManager->persist($seanceProfil);
            $entityManager->flush();
            return $this->redirectToRoute('seance_show', ['seanceID' => $seance->getID()]);
        }

        return $this->render('inscription/inscription.html.twig', [
            'seanceProfil' => $seanceProfil,
            'form' => $form->createView(),
            'seance' => $seance ,
        ]);
    }
}
