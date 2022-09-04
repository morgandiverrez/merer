<?php

namespace App\Controller;

use DateTime;
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

    #[Route('/{seanceID}', name: 'general')]
    #[IsGranted('ROLE_USER')]
    public function distribution(EntityManagerInterface $entityManager, Request $request, $seanceID): Response
    {
        $seance = $entityManager->getRepository(Seance::class)->findByID(strval($seanceID))[0];
        if(empty($seance->getGroupe())){
            return $this->redirectToRoute('inscription_ponctuelle', ['seanceID' => $seance->getId()]);
        }else{
             return $this->redirectToRoute('inscription_WEF', ['groupe' => $seance->getGroupe()]);
        }
    }

    #[Route('/ponctuelle/{seanceID}', name: 'ponctuelle')]
    #[IsGranted('ROLE_USER')]
    public function ponctuelle(EntityManagerInterface $entityManager, Request $request, $seanceID): Response
    {
        $seance = $entityManager->getRepository(Seance::class)->findByID(strval($seanceID))[0];
        if( null != $seance->getGroupe()){
            return $this->redirectToRoute('inscription_WEF', ['groupe' => $seance->getGroupe()]);
        }

        $seanceProfil = new SeanceProfil();
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

    #[Route('/WEF/{group}', name: 'WEF')]
    #[IsGranted('ROLE_USER')]
    public function wef( EntityManagerInterface $entityManager, Request $request, $group): Response
    {
        $listSubGroups= [];
        $subGroups = [];
        $seanceObligatoires = [];

        $seances = $entityManager->getRepository(Seance::class)->findAllByGroupe($group); //on recup tt les seances qui ont un groupe qui commence par la variable groupe
        $user = $this->getUser(); //on recup l'user 
        $profils = $entityManager->getRepository(Profil::class)->findAll(); // on recup tt les profils
        foreach ($profils as $testProfil) { // on itere pour trouver le bon profil
            if ($testProfil->getUser() == $user) {
                $profil = $testProfil;
            }
        }

        foreach($seances as $seance){  // pour chaque seance
            if (! isset($subGroups[$seance->getDatetime()->format('Y-m-d H:i')])) { //si il y a bien un sous groupe
                $subGroups[$seance->getDatetime()->format('Y-m-d H:i')] =[];
            }

            if (isset(explode("_", $seance->getGroupe())[1])) {//si il y a bien un sous groupe
                if(! in_array(explode("_", $seance->getGroupe())[1], $listSubGroups)) { //et s'il n'est pas dans la liste
                    array_push($listSubGroups, explode("_", $seance->getGroupe())[1]); // on ajoute ce sousGroupes
                }
                
                $subGroups[$seance->getDatetime()->format('Y-m-d H:i')][$seance->getGroupe()]= $seance; // on tri les seances par sousGroupes
            }else{
                array_push($seanceObligatoires, $seance); // on ajoute cette seance à la liste des seance obligatoire s'il n'ont pas se sous_groupe
            }            
        }

        foreach($seanceObligatoires as $seanceObligatoire){
            foreach($listSubGroups as $subGroup){
                $subGroups[$seanceObligatoire->getDatetime()->format('Y-m-d H:i')][$group."_".$subGroup] = $seance;
            }
        }
            
        if ($request->isMethod('post')) {
            $posts = $request->request->all();
            foreach ($seances as $seance) {
                if (isset($posts['indisponibilite_' . $seance->getId()])) {
                    $seanceProfil = new SeanceProfil();
                    $seanceProfil->setSeance($seance);
                    $seanceProfil->setProfil($profil);
                    $seanceProfil->setHorrodateur(new DateTime());
                    $seanceProfil->setAttente($posts['attentes_' . $seance->getId()]);
                    $seanceProfil->setLieu($seance->getLieux()[0]);
                   // $seanceProfil->isAutorisationPhoto($posts['autorisation_photo']);
                    //  $seanceProfil->setModePaiement($posts['mode_paiement']);
                    // $seanceProfil->setCovoiturage($posts['covoiturage']);
                    $entityManager->persist($seanceProfil);
                }
            }
          
            $entityManager->flush();
            return  $this->redirectToRoute('profil_show',[]);
        }

        
        else{
            return $this->render('inscription/WEF.html.twig', [
                'seances'=> $seances,
                'group'=>$group,
                'subGroups' => $subGroups,
                'listSubGroups' => $listSubGroups,
            ]);
        }
    }
   

    #[Route('/delete/{seanceID}/{profilID}', name: 'delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(EntityManagerInterface $entityManager, $seanceID, $profilID): Response
    {
        
        $seanceProfil = $entityManager->getRepository(SeanceProfil::class)->findBy2Id($seanceID, $profilID)[0];
        $entityManager->remove($seanceProfil);
        $entityManager->flush();

        return $this->redirectToRoute('seance_liste_inscrit', ['seanceID' => $seanceID]);
    }
} 


