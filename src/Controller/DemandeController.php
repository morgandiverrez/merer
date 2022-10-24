<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Entity\Demande;
use App\Form\DemandeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/demande', name: 'demande_')]

class DemandeController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    #[IsGranted('ROLE_FORMATEURICE')]
    public function showAll(EntityManagerInterface $entityManager): Response
    {
        $demandes = $entityManager->getRepository(Demande::class)->findAllOrderByDateDebut();
      
        return $this->render('demande/showAll.html.twig', [
            'demandes' => $demandes,

        ]);
    }

    #[Route('/show/{demandeID}', name: 'show')]
    #[IsGranted('ROLE_FORMATEURICE')]
    public function show(EntityManagerInterface $entityManager, Request $request,  $demandeID): Response
    {
        $demande = $entityManager->getRepository(Demande::class)->findById($demandeID)[0];


      
        return $this->render('demande/show.html.twig', [
            'demande' => $demande,

        ]);
    }

    #[Route('/downloadPlanning/{demandeID}', name: 'download')]
    #[IsGranted('ROLE_FORMATEURICE')]
    public function downloadPlanning(EntityManagerInterface $entityManager, Request $request,  $demandeID)
    {
        $demande = $entityManager->getRepository(Demande::class)->findById($demandeID)[0];

        $finaleFile = $demande->getPlanning();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($finaleFile) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($finaleFile));
        readfile($finaleFile);

        return $this->redirectToRoute('demandes_show',[]);
    }

    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_USER')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $demande = new Demande();
        $form = $this->createForm(DemandeType::class, $demande);
        $form->handleRequest($request);

        $user = $this->getUser(); //on recup l'user 
        $profils = $entityManager->getRepository(Profil::class)->findAll(); // on recup tt les profils
        foreach ($profils as $testProfil) { // on itere pour trouver le bon profil
            if ($testProfil->getUser() == $user) {
                $profil = $testProfil;
            }
        }
        
        if ($form->isSubmitted() && $form->isValid()) {
            if( $demande->getDateFin() != null){ $demande->setDateFin($demande->getDateDebut()); }
            $logoUpload = $form->get('planning')->getData();

            if ($logoUpload) {
                $planningFileName = 'planning_'.$demande->getId().'.' . $logoUpload->guessExtension();
                $demande->setPlanning('public/build/demande/planning/' . $planningFileName);
                try {
                    $logoUpload->move(
                        'public/build/demande/planning',
                        $planningFileName
                    );
                } catch (FileException $e) {
                }
            }
            $demande->setProfil($profil);
            $entityManager->persist($demande);
            $entityManager->flush();
            return $this->redirectToRoute('demande_show', ['demandeID' => $demande->getId()]);
        }

        return $this->render('demande/new.html.twig', [
            'demande' => $demande,
            'form' => $form->createView(),
        ]);
    }



   
    #[Route('/delete/{demandeID}', name: 'delete')]
    #[IsGranted('ROLE_FORMA')]
    public function delete(EntityManagerInterface $entityManager, $demandeID): Response
    {

        $demande = $entityManager->getRepository(Demande::class)->findById($demandeID)[0];
        $entityManager->remove($demande);
        $entityManager->flush();

        return $this->redirectToRoute('demande_showAll');
    }

   
}
