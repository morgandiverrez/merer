<?php

namespace App\Controller;

use DateTime;
use App\Form\WEFType;
use App\Entity\Profil;
use App\Entity\Seance;
use App\Entity\SeanceProfil;
use App\Form\PonctuelleType;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/inscription', name: 'inscription_')]
class InscriptionController extends AbstractController
{
    #[Route('/ponctuelle/{seanceID}', name: 'ponctuelle')]
    #[IsGranted('ROLE_USER')]
    public function ponctuelle(EntityManagerInterface $entityManager, Request $request, $seanceID): Response
    {
        $seanceProfil = new SeanceProfil();
        $seance = $entityManager->getRepository(Seance::class)->findByID(strval($seanceID))[0];
        $form = $this->createForm(PonctuelleType::class, $seanceProfil, [ 'liste_lieu' => $seance->getLieux()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $user = $this->getUser();
            $profils = $entityManager->getRepository(Profil::class)->findAll();
            foreach ($profils as $testProfil) {
                if ($testProfil->getUser() == $user) {
                    $profil = $testProfil;
                }
            }
            $seanceProfil->setHorrodateur(new DateTime());
            $profil->addSeanceProfil($seanceProfil);
            $seance = $entityManager->getRepository(Seance::class)->findByID(strval($seanceID))[0];
            $seance->addSeanceProfil($seanceProfil);
            $entityManager->persist($seanceProfil);
            $entityManager->flush();

            return $this->redirectToRoute('seance_show', ['seanceID' => $seance->getID()]);
        }

        return $this->render('inscription/ponctuelle.html.twig', [
            'seanceProfil' => $seanceProfil,
            'form' => $form->createView(),
            'seance' => $seance ,
        ]);
    }

    #[Route('/petitWEF/{groupe}', name: 'petitWEF')]
    #[IsGranted('ROLE_USER')]
    public function petit(EntityManagerInterface $entityManager, Request $request, $groupe): Response
    {

        $seances = $entityManager->getRepository(Seance::class)->findAllByGroupe($groupe);
        $form = $this->createForm(WEFType::class);
        $form->handleRequest($request);
        $user = $this->getUser();
        $profils = $entityManager->getRepository(Profil::class)->findAll();
            foreach ($profils as $testProfil) {
                if ($testProfil->getUser() == $user) {
                    $profil = $testProfil;
                }
            }
        
        if ($form->isSubmitted() && $form->isValid()) {
            foreach($seances as $seance){
                $seanceProfil = new SeanceProfil();
                $seanceProfil->setAutorisationPhoto( $form-> get('autorisation_photo')->getData());
                $seanceProfil->setModePaiement($form->get('mode_paiement')->getData());
                $seanceProfil->setCovoiturage($form->get('covoiturage')->getData());
                $seanceProfil->setHorrodateur(new DateTime());

                $profil->addSeanceProfil($seanceProfil);
                $seance->addSeanceProfil($seanceProfil);

                $entityManager->persist($seanceProfil);
            }

            $entityManager->flush();
            return $this->redirectToRoute('seance_show', ['seanceID' => $seance->getID()]);
        }

        return $this->render('inscription/petitwef.html.twig', [
            'form' => $form->createView(),
            'seances'=> $seances
        ]);
    }

}
    // #[Route('/delete/{seanceID}/{profilID}', name: 'delete')]
    // #[IsGranted('ROLE_ADMIN')]
    // public function delete(EntityManagerInterface $entityManager, $seanceID, $profilID): Response
    // {
        
    //     $seanceProfil = $entityManager->getRepository(SeanceProfil::class)->findBy2Id($seanceID, $profilID)[0];
    //     $entityManager->remove($seanceProfil);
    //     $entityManager->flush();

    //     return $this->redirectToRoute('seance_liste_inscrit', ['seanceID' => $seanceID]);
    // }
    


