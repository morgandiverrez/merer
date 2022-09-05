<?php

namespace App\Controller;

use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Lieux;
use App\Entity\Profil;
use App\Entity\Retour;
use App\Entity\Seance;
use App\Form\SeanceType;
use App\Entity\Formation;
use App\Entity\SeanceProfil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[Route('/seance', name: 'seance_')]

class SeanceController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    #[IsGranted('ROLE_USER')]
    public function showAll(EntityManagerInterface $entityManager): Response
    {
        $dateActuelle =new DateTime();
        
        $seances = $entityManager->getRepository(Seance::class)->findAllByDatetime($dateActuelle);
        
        return $this->render('seance/showAll.html.twig', [
            'seances' => $seances, 
        ]);
    }

    #[Route('/archivage', name: 'archivage')]
    #[IsGranted('ROLE_FORMATEURICE')]
    public function archivage(EntityManagerInterface $entityManager, Request $request): Response
    {
        $seances = $entityManager->getRepository(Seance::class)->findAllOrderByDate();

        if ($request->isMethod('post')) {
            $posts = $request->request->all();
            if ($posts['name']) {
                $seances = array_intersect($seances, $entityManager->getRepository(Seance::class)->findAllByName($posts['name']));
            }
            if ($posts['groupe']) {
                $seances = array_intersect($seances, $entityManager->getRepository(Seance::class)->findAllByGroupe($posts['groupe']));
            }
            if ($posts['formation']) {
                $seances = array_intersect($seances, $entityManager->getRepository(Formation::class)->findAllByName($posts['formation']));
            }
            if ($posts['nom_lieu']) {
                $lieux = $entityManager->getRepository(Lieux::class)->findAllByName($posts['nom_lieu']);
                $lieuNameSeance = array();
                foreach ($lieux as $lieu) {
                    foreach ($lieu->getSeance() as $seance) {
                        array_push($lieuNameSeance, $seance);
                    }
                }
                $seances = array_intersect($seances, $lieuNameSeance);
            }
            if ($posts['ville']) {
                $lieux = $entityManager->getRepository(Lieux::class)->findAllByVille($posts['ville']);
                $lieuSeance = array();
                foreach ($lieux as $lieu) {
                    foreach ($lieu->getSeance() as $seance) {
                        array_push($lieuSeance, $seance);
                    }
                }
                $seances = array_intersect($seances, $lieuSeance);
            }
            if ($posts['datedebut']) {
                $seances = array_intersect($seances, $entityManager->getRepository(Seance::class)->findAllByDateTimeSuperior($posts['datedebut']));
            }
            if ($posts['datefin']) {
                $seances = array_intersect($seances, $entityManager->getRepository(Seance::class)->findAllByDateTimeInferior($posts['datefin']));
            }      
        }

        return $this->render('seance/archivage.html.twig', [
            'seances' => $seances,
        ]);
    }


    #[Route('/showForFormateurice/{seanceID}', name: 'showForFormateurice')]
    #[IsGranted('ROLE_FORMATEURICE')]
    public function showForFormateurice(EntityManagerInterface $entityManager, $seanceID): Response
    {
        $seance = $entityManager->getRepository(Seance::class)->findByID($seanceID)[0];

        return $this->render('seance/showForFormateurice.html.twig', [
            'seance' => $seance,
        ]);
    }

    #[Route('/show/{seanceID}', name: 'show')]
    #[IsGranted('ROLE_USER')]
    public function show(EntityManagerInterface $entityManager, $seanceID): Response
    {
        $seance = $entityManager->getRepository(Seance::class)->findByID($seanceID)[0];
        $inscrits = $entityManager->getRepository(SeanceProfil::class)->findAllBySeance($seance);
        $retours = $entityManager->getRepository(Retour::class)->findBySeance($seance);
        $dateActuelle = new DateTime();
        return $this->render('seance/show.html.twig', [
            'seance' => $seance,
            'inscrits' => $inscrits,
            'dateActuelle' => $dateActuelle,
            'retours' => $retours,
       ]);
    }

    #[Route('/liste_inscrit/{seanceID}', name: 'liste_inscrit')]
    #[IsGranted('ROLE_FORMATEURICE')]
    public function listeInscrit(EntityManagerInterface $entityManager, $seanceID): Response
    {
        $seance = $entityManager->getRepository(Seance::class)->findByID($seanceID)[0];

        return $this->render('seance/listeInscrit.html.twig', [
            'seance' => $seance,
        ]);
    }

    #[Route('/liste_inscrit/pdf/{seanceID}', name: 'liste_inscrit_pdf')]
    #[IsGranted('ROLE_FORMATEURICE')]
    public function inscriptionPDF(EntityManagerInterface $entityManager, $seanceID)
    {

        $seance = $entityManager->getRepository(Seance::class)->findByID($seanceID)[0];

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($pdfOptions);

        $dompdf->set_option('isHtml5ParserEnabled', true);

        $html = $this->renderView('seance/listeInscritPDF.html.twig', [
            'title' => "Welcome to our PDF Test",
            'seance' => $seance,
        ]);

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $dompdf->stream("inscription.pdf", [
            "Attachment" => true
        ]);
    }



    #[Route('/edit/{seanceID}', name: 'edit')]
    #[IsGranted('ROLE_BF')]
    public function edit(EntityManagerInterface $entityManager, Request $request, $seanceID): Response
    {
        $seance = $entityManager->getRepository(Seance::class)->findById($seanceID)[0];
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            $go = true;
            $i = 0;
            while ($go) {
                if (isset($form->get('profil')->getData()[$i])) {       
                    $nameLastNameProfil = $form->get('profil')->getData()[$i];
                    list($nameProfil, $lastNameProfil) = explode(" ", $nameLastNameProfil);
                    $profil = $entityManager->getRepository(Profil::class)->findByName(strval($nameProfil),strval($lastNameProfil))[0];
                    $profil->addSeance($seance);
                    $entityManager->persist($profil);

                    $i++;
                } else {
                    $go = false;
                }
            }

            $go = true;
            $i = 0;
            while ($go) {
                if (isset($form->get('lieux')->getData()[$i])) {
                    $nameLieux = $form->get('lieux')->getData()[$i];
                    $lieux = $entityManager->getRepository(Lieux::class)->findByName(strval($nameLieux))[0];
                    $lieux->addSeance($seance);
                    $entityManager->persist($lieux);

                    $i++;
                } else {
                    $go = false;
                }
            }

            if ($form->get('formation')->getData()) {
                $nameFormation = $form->get('formation')->getData();
                $formation = $entityManager->getRepository(Formation::class)->findByName(strval($nameFormation))[0];
                $formation->addSeance($seance);
                $entityManager->persist($formation);

                $i++;
            }

        

            $entityManager->persist($seance);
            $entityManager->flush();
            return $this->redirectToRoute('seance_show', ['seanceID' => $seance->getID()]);
        }

        return $this->render('seance/edit.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
        
        ]);
    }
   
    // #[Route('/group/{seanceID}', name: 'group')]
    // #[IsGranted('ROLE_BF')]
    // public function choiceGroup(EntityManagerInterface $entityManager, Request $request,  $seanceID): Response
    // {
    //     $seance = $entityManager->getRepository(Seance::class)->findById($seanceID)[0];
    //     $currentGroup = explode("_", $seance->getGroupe())[0];

    //     $listesGroups = $entityManager->getRepository(Seance::class)->findAllGroupe();
    //     $arrayGroups = array() ;
    //     foreach($listesGroups as $group){ 
    //         $groupxplode = explode("_", $group['groupe']);
    //         if(! in_array($groupxplode[0], $arrayGroups)){
    //             array_push($arrayGroups, $groupxplode[0]);
    //         } 
    //     }

    //     if ($request->isMethod('post')) {
    //         $posts = $request->request->all();
    //         if ($posts['groupe'] and $posts['groupe']!='') {
    //             $groupSelect = $posts['groupe'];
    //         }elseif($posts['newGroup']){
    //             $groupSelect = $posts['newGroup'];
    //         }else{
    //             return $this->redirectToRoute('seance_showForFormateurice', ['seanceID' => $seance->getID()]);
    //         }
    //         return $this->redirectToRoute('seance_subGroup', ['seanceID' => $seance->getID(), 'group' => strtoupper($groupSelect)]);
    //     }
    //     return $this->render('seance/choiceGroup.html.twig', [
    //         'seance' => $seance,
    //         'listeGroups' => $arrayGroups,
    //         'currentGroup' => $currentGroup,

    //     ]);
    // }

    //  #[Route('/sub_group/{seanceID}/{group}', name: 'subGroup')]
    // #[IsGranted('ROLE_BF')]
    // public function choiceSubGroup(EntityManagerInterface $entityManager, $group, $seanceID ,Request $request): Response
    // {
    //     $seance = $entityManager->getRepository(Seance::class)->findById($seanceID)[0];
    //     $currentSubGroup ="";
    //     if(isset(explode("_", $seance->getGroupe())[1])) {
    //         $currentSubGroup = explode("_", $seance->getGroupe())[1];
    //     }
    //     $listesGroups = $entityManager->getRepository(Seance::class)->findAllSousGroupe($group);
    //     $arraySubGroups = [];
    //     foreach ($listesGroups as $subGroup) {
    //         $subGroupxplode = explode("_", $subGroup['groupe']);

    //         if (isset($subGroupxplode[1]) and ! in_array($subGroupxplode[1], $arraySubGroups)){
    //             array_push($arraySubGroups, $subGroupxplode[1]);
    //         }
    //     }

    //     if ($request->isMethod('post')) {
    //         $posts = $request->request->all();
    //         if ($posts['subGroup'] and $posts['subGroup']!='0') {
    //             $seance->setGroupe($group.'_'.strtoupper($posts['subGroup']));
    //         } elseif ($posts['newSubGroup']) {
    //             $seance->setGroupe($group.'_'.strtoupper($posts['newSubGroup']));
    //         } else {
    //             $seance->setGroupe($group);
    //         }
    //         $entityManager->persist($seance);
    //         $entityManager->flush();
    //         return $this->redirectToRoute('seance_showForFormateurice', ['seanceID' => $seance->getID()]);
    //     }
    //     print_r($arraySubGroups);
    //     return $this->render('seance/choiceSubGroup.html.twig', [
    //         'seance' => $seance,
    //         'group'=> $group,
    //         'listeSubGroups' => $arraySubGroups,
    //         'currentSubGroup' => $currentSubGroup,

    //     ]);
    // }

    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_BF')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $seance = new Seance();
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
         
           foreach($form->get('profil')->getData() as  $formateurice){
                $nameLastNameFormateurice = $formateurice;
                $namelastname = explode(" ", $nameLastNameFormateurice);
                $profil = $entityManager->getRepository(Profil::class)->findByName($namelastname[0], $namelastname[1])[0];
                $profil->addSeance($seance);
                $entityManager->persist($profil);
            }

            foreach ($form->get('lieux')->getData() as  $lieu) {
                    $nameLieux = $lieu;
                    $lieux = $entityManager->getRepository(Lieux::class)->findByName(strval($nameLieux))[0];
                    $lieux->addSeance($seance);
                    $entityManager->persist($lieux);
            }
           
            if ($form->get('formation')->getData()) {
                $nameFormation = $form->get('formation')->getData();
                $formation = $entityManager->getRepository(Formation::class)->findByName(strval($nameFormation))[0];
                $formation->addSeance($seance);
                $entityManager->persist($formation);
            } 
    
            $entityManager->persist($seance);
            $entityManager->flush();
            return $this->redirectToRoute('seance_show', ['seanceID' => $seance->getID()]);
        }

        return $this->render('seance/new.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(), 
        ]);
    }
}