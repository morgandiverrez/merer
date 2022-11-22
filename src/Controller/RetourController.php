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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/retour', name: 'retour_')]

class RetourController extends AbstractController
{
    #[Route('/new/{seanceID}', name: 'new')]
    #[IsGranted('ROLE_USER')]
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

            if($seance->getFormation()->getBadge()){
                $profil -> addBadge($seance-> getFormation() -> getBadge());
            }
            $entityManager->persist($retour);
            $entityManager->persist($profil);
            $entityManager->flush();
            return $this->redirectToRoute('profil_show', []);
        }

        return $this->render('retour/new.html.twig', [
            'retour' => $retour,
            'form' => $form->createView(),
        ]);
    }


     #[Route('/sdf', name: 'sdf')]
    #[IsGranted('ROLE_FORMA')]
    public function edit(EntityManagerInterface $entityManager)
    {
        $seances = $entityManager->getRepository(Seance::class)->findAllByYear(date("Y")."-01-01", strval(intval(date("Y"))+1)."-01-01");
        $inputFileName = '\public\public\files\SDF\SDF_vierge.xlsx';
        /** Load $inputFileName to a Spreadsheet object **/
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);

        foreach( $seances as $seance){
            $clonedWorksheet = clone $spreadsheet->getSheetByName('Modèle vierge'); //créer une nouvelle feuille
            $clonedWorksheet->setTitle($seance->getName() . strval($seance->getDatetime()->format("Y-m-d"))); //on nomme la nouvelle feuille
            $spreadsheet->addSheet($clonedWorksheet); // on ajoute au classeur la nouvelle feuille
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx"); // on ouvre en ecriture le classeur

            $spreadsheet->getSheetByName($clonedWorksheet->getTitle())->getCell('F7')->setValue($seance->getName()); // inscription intitulé formation
            $spreadsheet->getSheetByName($clonedWorksheet->getTitle())->getCell('L8')->setValue($seance->getDatetime()->format("Y-m-d")); // on met la date sur la feuille active
            $spreadsheet->getSheetByName($clonedWorksheet->getTitle())->getCell('N10')->setValue($seance->getGroupe()); // 

            $i = 8;
            foreach( $seance->getProfil() as $formateurice){
                $spreadsheet->getSheetByName($clonedWorksheet->getTitle())->getCell('F'.$i)->setValue($formateurice->getName()." ".$formateurice->getLastname());
                $i++;
            }

            $i = 10;
            foreach ($seance->getLieux() as $lieu) {
                $spreadsheet->getSheetByName($clonedWorksheet->getTitle())->getCell('F'.$i)->setValue($lieu->getName());
                $i++;
            }
            
            $colonne = ['G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE'];
            $i = 0;
            foreach( $seance->getRetour() as $retour){
                $spreadsheet->getSheetByName($clonedWorksheet->getTitle())->getCell($colonne[$i].'15')->setValue( strval($retour->getNoteContenu()));
                $spreadsheet->getSheetByName($clonedWorksheet->getTitle())->getCell($colonne[$i].'16')->setValue( strval($retour->getNoteAnimation()));
                $spreadsheet->getSheetByName($clonedWorksheet->getTitle())->getCell($colonne[$i].'17')->setValue( strval($retour->getNoteImplication()));
                $spreadsheet->getSheetByName($clonedWorksheet->getTitle())->getCell($colonne[$i].'18')->setValue( strval($retour->getNoteReponseAtente()));
                $spreadsheet->getSheetByName($clonedWorksheet->getTitle())->getCell($colonne[$i].'19')->setValue(strval($retour->getNoteNivCompetence()));
                $spreadsheet->getSheetByName($clonedWorksheet->getTitle())->getCell($colonne[$i].'20')->setValue( strval($retour->getNoteUtilite()));
                $spreadsheet->getSheetByName($clonedWorksheet->getTitle())->getCell($colonne[$i].'21')->setValue( strval($retour->getNoteGenerale()));
                $i++;
            }
        }

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save("SDF_" . date("Y").".xlsx");
        $finaleFile = "C:\wamp64\www\Formation_FedeB\public\SDF_". date("Y")."xlsx";
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($finaleFile) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($finaleFile));
        readfile($finaleFile);
       
        
        
        
    }

    #[Route('/resultat/{seanceID}', name: 'resultat')]
    #[IsGranted('ROLE_FORMATEURICE')]
    public function resultat(ChartBuilderInterface $chartBuilder, EntityManagerInterface $entityManager, $seanceID): Response
    {

        $seance = $entityManager->getRepository(Seance::class)->findByID($seanceID);
        $retours = $entityManager->getRepository(Retour::class)->findBySeance($seance);

        $dataSomme=array([0, 0, 0, 0, 0, 0, 0], 
                         [0, 0, 0, 0, 0, 0, 0],
                         [0, 0, 0, 0, 0, 0, 0],
                         [0, 0, 0, 0, 0, 0, 0],
                         [0, 0, 0, 0, 0, 0, 0]);
       
        $dataMoyenne = array(0, 0, 0, 0, 0, 0, 0);

        $dataApport = array(0, 0, 0, 0);

        $dataRemarque = array('','','','','','','','','','','',''); 
        
        $dataQuestion = array('Satisfaction générale vis-à-vis de la formation', 'Pensez-vous que les compétences acquises durant cette formation seront utiles dans votre parcours de associatif ?', 'Comment évaluez-vous le niveau de compétences techniques (connaissances du sujet, exposé) et pédagogiques (débit de parole, charisme, échange) des formateurices ?', 'Cette formation a-t-elle répondu à vos attentes ?', 'Comment évaluez-vous l\'implication des participant.e.s à la formation ?', 'Comment évaluez-vous l’animation [séquence, utilisation du matériel, débats] de la formation ?', 'Comment évaluez-vous le contenu de la formation (informations adaptées au public, compréhension, technicité) ?','Qu\'est-ce que tu as aimé ? ','Qu\'est-ce que tu as moins aimé ?', 'Qu\'est-ce que tu aurais aimé voir dans cette formation ?','Mot de la fin');
       
        foreach( $retours as $retour){ // indentation pour passer par chaque retour
            $note = $retour ->getNoteGenerale();
            $critere = 0;
            $dataSomme[$note - 1][$critere] += 1; // ajout d'un 1 en fonction de la valeur du retour
            $dataMoyenne[$critere] += $note;
            
            $remarque = $retour->getRemarqueGenerale();
            $dataRemarque[$critere] .= ',' . $remarque;
        
            // indentation pour passer par chaque retour
            $note = $retour->getNoteUtilite();
            $critere = 1;
            $dataSomme[$note - 1][$critere] += 1; // ajout d'un 1 en fonction de la valeur du retour
            $dataMoyenne[$critere] += $note;

            $remarque = $retour->getRemarqueUtilite();
            $dataRemarque[$critere] .= ',' . $remarque;
        
            // indentation pour passer par chaque retour
            $note = $retour->getNoteNivCompetence();
            $critere = 2;
            $dataSomme[$note - 1][$critere] += 1; // ajout d'un 1 en fonction de la valeur du retour
            $dataMoyenne[$critere] += $note;

            $remarque = $retour->getRemarqueNivCompetence();
            $dataRemarque[$critere] .= ',' . $remarque;
        
            // indentation pour passer par chaque retour
            $note = $retour->getNoteReponseAtente();
            $critere = 3;
            $dataSomme[$note - 1][$critere] += 1; // ajout d'un 1 en fonction de la valeur du retour
            $dataMoyenne[$critere] += $note;

            $remarque = $retour->getRemarqueReponseAttente();
            $dataRemarque[$critere] .= ',' . $remarque;
        
            // indentation pour passer par chaque retour
            $note = $retour->getNoteImplication();
            $critere = 4;
            $dataSomme[$note - 1][$critere] += 1; // ajout d'un 1 en fonction de la valeur du retour
            $dataMoyenne[$critere] += $note;

            $remarque = $retour->getRemarqueImplication();
            $dataRemarque[$critere] .= ',' . $remarque;
        
                                // indentation pour passer par chaque retour
            $note = $retour->getNoteAnimation();
            $critere = 5;
            $dataSomme[$note - 1][$critere] += 1; // ajout d'un 1 en fonction de la valeur du retour
            $dataMoyenne[$critere] += $note;


            $remarque = $retour->getRemarqueAnimation();
            $dataRemarque[$critere] .= ',' . $remarque;
       
            // indentation pour passer par chaque retour
            $note = $retour->getNoteContenu();
            $critere = 6;
            $dataSomme[$note - 1][$critere] += 1; // ajout d'un 1 en fonction de la valeur du retour
            $dataMoyenne[$critere] += $note;

            $remarque = $retour->getRemarqueContenu();
            $dataRemarque[$critere] .= ',' . $remarque;
        
        
            $remarque = $retour->getPlusAimer();
            $dataRemarque[7] .= ',' . $remarque;

            $remarque = $retour->getMoinsAimer();
            $dataRemarque[8] .= ',' . $remarque;

            $remarque = $retour->getAimerVoir();
            $dataRemarque[9] .= ',' . $remarque;

            $remarque = $retour->getMotFin();
            $dataRemarque[10] .= ',' . $remarque;
        
            // indentation pour passer par chaque retour
            $choix = $retour->getApportGenerale();

            switch ($choix) {
                case ('ne m\'a rien apporté'):
                    $dataApport[0] += 1; // ajout d'un 1 en fonction de la valeur du retour
                   
                    break;
                case ('a confirmé ce que je savais déjà'):
                    $dataApport[1] += 1; // ajout d'un 1 en fonction de la valeur du retour
                   
                    break;
                case ('m\'a apporté de nouvelles connaissances'):
                    $dataApport[2] += 1; // ajout d'un 1 en fonction de la valeur du retour
                   
                    break;
                case ('m\'a permis d`\'échanger avec les participant.e.s'):
                    $dataApport[3] += 1; // ajout d'un 1 en fonction de la valeur du retour
                   
                    break;
                
            }
            
           
        }
        
        
        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);

        $chartApport = $chartBuilder->createChart(Chart::TYPE_BAR);

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

        $chartApport->setData([
            'labels' => ['Cette formation :'],
            'datasets' => [
                [
                    'label' => 'ne m\'a rien apporté',
                    'backgroundColor' => 'blue',
                    'stack' => 'Stack 0',
                    'data' => array($dataApport[0]),
                ],

                [
                    'label' => 'a confirmé ce que je savais déjà',
                    'backgroundColor' => 'red',
                    'stack' => 'Stack 0',
                    'data' => array($dataApport[1]),
                ],

                [
                    'label' => 'm\'a apporté de nouvelles connaissances',
                    'backgroundColor' => 'yellow',
                    'stack' => 'Stack 0',
                    'data' => array($dataApport[2]),
                ],

                [
                    'label' => 'm\'a permis d`\'échanger avec les participant.e.s',
                    'backgroundColor' => 'green',
                    'stack' => 'Stack 0',
                    'data' => array($dataApport[3]),
                ],

            ],
        ]);

        $chartApport->setOptions([
            'indexAxis' => 'y',
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

        return $this->render('retour/afficheur.html.twig', [
            'chart' => $chart,
            'chartApport' => $chartApport,
            'dataApport' => $dataApport,
            'dataMoyenne' => $dataMoyenne,
            'dataRemarque' => $dataRemarque,
            'dataQuestion' => $dataQuestion,
        ]);
    }
}
