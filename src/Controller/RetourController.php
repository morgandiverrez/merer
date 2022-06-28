<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Entity\Retour;
use App\Entity\Seance;
use App\Form\RetourType;
use Symfony\UX\Chartjs\Model\Chart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/retour', name: 'retour_')]

class RetourController extends AbstractController
{
    #[Route('/new/{seanceID}', name: 'new')]
    public function new(EntityManagerInterface $entityManager, Request $request, $seanceID): Response
    {
        $retour = new Retour();

        $seance = $entityManager->getRepository(Seance::class)->findByID(strval($seanceID))[0];
        $retour->setSeance($seance);

        $user = $this->getUser();
        $profils = $entityManager->getRepository(Profil::class)->findAll();
        foreach ($profils as $testProfil) {
            if ($testProfil->getUser() == $user) {
                $profil = $testProfil;
            }
        }
        $retour->setProfil($profil);
        
        $form = $this->createForm(RetourType::class, $retour);
        $form->handleRequest($request);
       

        if ($form->isSubmitted() && $form->isValid()) {

            

            $entityManager->persist($retour);
            $entityManager->flush();
            return $this->redirectToRoute('profil_show', []);
        }

        return $this->render('retour/new.html.twig', [
            'retour' => $retour,
            'form' => $form->createView(),
           
        ]);
    }

    #[Route('/resultat/{seanceID}', name: 'resultat')]
    public function resultat(ChartBuilderInterface $chartBuilder, EntityManagerInterface $entityManager, $seanceID): Response
    {

        $seance = $entityManager->getRepository(Seance::class)->findByID($seanceID);
        $retours = $entityManager->getRepository(Retour::class)->findBySeance($seance);

        $dataSomme=array([0, 0, 0, 0, 0, 0, 0], 
                         [0, 0, 0, 0, 0, 0, 0],
                         [0, 0, 0, 0, 0, 0, 0],
                         [0, 0, 0, 0, 0, 0, 0],
                         [0, 0, 0, 0, 0, 0, 0]);
       
        $dataMoyenne= array(0, 0, 0, 0, 0, 0, 0);
        
        foreach( $retours as $retour){ // indentation pour passer par chaque retour
            $note = $retour ->getNoteGenerale();
                $dataSomme[$note - 1][0] += 1; // ajout d'un 1 en fonction de la valeur du retour
                $dataMoyenne[0] += $note;
        }

        foreach ($retours as $retour) { // indentation pour passer par chaque retour
            $note = $retour->getNoteUtilite();
            $dataSomme[$note - 1][1] += 1; // ajout d'un 1 en fonction de la valeur du retour
            $dataMoyenne[1] += $note;
        }

        foreach ($retours as $retour) { // indentation pour passer par chaque retour
            $note = $retour->getNoteNivCompetence();
            $dataSomme[$note - 1][2] += 1; // ajout d'un 1 en fonction de la valeur du retour
            $dataMoyenne[2] += $note;
        }

        foreach ($retours as $retour) { // indentation pour passer par chaque retour
            $note = $retour->getNoteReponseAtente();
            $dataSomme[$note - 1][3] += 1; // ajout d'un 1 en fonction de la valeur du retour
            $dataMoyenne[3] += $note;
        }

        foreach ($retours as $retour) { // indentation pour passer par chaque retour
            $note = $retour->getNoteImplication();
            $dataSomme[$note - 1][4] += 1; // ajout d'un 1 en fonction de la valeur du retour
            $dataMoyenne[4] += $note;
        }

        foreach ($retours as $retour) { // indentation pour passer par chaque retour
            $note = $retour->getNoteAnimation();
            $dataSomme[$note - 1][5] += 1; // ajout d'un 1 en fonction de la valeur du retour
            $dataMoyenne[5] += $note;
        }

        foreach ($retours as $retour) { // indentation pour passer par chaque retour
            $note = $retour->getNoteContenu();
            $dataSomme[$note - 1][6] += 1; // ajout d'un 1 en fonction de la valeur du retour
            $dataMoyenne[6] += $note;
        } 

        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);

        $chart->setData([
            'labels' => ['Satisfaction générale vis-à-vis de la formation','Utilité des compétences acquises', 'Compétences techniques et pédagogiques des formateurices', 'Cette formation a-t-elle répondu à vos attentes ?', 'L\'implication des participant.e.s', 'L\'animation de la formation', 'Le contenu de la formation'],
            'datasets' => [
                [
                    'label' => 'Mauvais',
                    'backgroundColor' => 'blue',
                    'stack' => 'Stack 0',
                    'data' => $dataSomme[0],
                ],

                [
                    'label' => 'Moyen',
                    'backgroundColor' => 'red',
                    'stack' => 'Stack 0',
                    'data' => $dataSomme[1],
                ],

                [
                    'label' => 'Correct',
                    'backgroundColor' => 'yellow',
                    'stack' => 'Stack 0',
                    'data' => $dataSomme[2],
                ],

                [
                    'label' => 'Bien',
                    'backgroundColor' => 'green',
                    'stack' => 'Stack 0',
                    'data' => $dataSomme[3],
                ],

                [
                    'label' => 'Très bien',
                    'backgroundColor' => 'orange',
                    'stack' => 'Stack 0',
                    'data' => $dataSomme[4],
                ],
            ],
        ]);

        $chart->setOptions([
            'indexAxis' =>'y',
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

        return $this->render('retour/afficheur.html.twig', [
            'chart' => $chart,
        ]);
    }
}
