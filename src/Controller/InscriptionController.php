<?php

namespace App\Controller;

use DateTime;
use App\Entity\Profil;
use App\Entity\Seance;
use App\Entity\Evenement;
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
        if(empty($seance->getEvenement())){
            return $this->redirectToRoute('inscription_ponctuelle', ['seanceID' => $seance->getId()]);
        }else{
             return $this->redirectToRoute('inscription_evenement', ['evenementID' => $seance->getEvenement()->getId()]);
        }
    }

    #[Route('/ponctuelle/{seanceID}', name: 'ponctuelle')]
    #[IsGranted('ROLE_USER')]
    public function ponctuelle(EntityManagerInterface $entityManager, Request $request, $seanceID): Response
    {
        $seance = $entityManager->getRepository(Seance::class)->findByID($seanceID)[0];
        if ($seance->isVisible()) {
            return $this->redirectToRoute('profil_show', []);
        }
        if( null != $seance->getEvenement()){
            return $this->redirectToRoute('inscription_evenement', ['evenementID' => $seance->getEvenement()->getId()]);
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

    #[Route('/evenement/{evenementID}', name: 'evenement')]
    #[IsGranted('ROLE_USER')]
    public function wef( EntityManagerInterface $entityManager, Request $request, $evenementID): Response
    {
        $seanceByCreneauAndParcours = [];

        $evenement = $entityManager->getRepository(Evenement::class)->findById($evenementID)[0];
        if ( $evenement->isVisible()) {
            return $this->redirectToRoute('profil_show', []);
        }
        $seances = $evenement->getSeance(); //on recup tt les seances qui ont un groupe qui commence par la variable groupe

       

        foreach($seances as $seance){   
            if( $seance->getParcours() != null ){ 
                $seanceByCreneauAndParcours[strval($seance->getDatetime()->format("d/m/Y H:i"))][$seance->getParcours()] = $seance;
            }else{ //si pas de parcours (donc formation pour tt les parcours si plusieur parcours)
                foreach($evenement->getParcours() as $parcours){ // on itere les parcours pour les remplir tous de cette seance
                    $seanceByCreneauAndParcours[$seance->getDatetime()->format("d/m/Y H:i")][$parcours] = $seance;
                }
            }
        }

        $user = $this->getUser(); //on recup l'user 
        $profils = $entityManager->getRepository(Profil::class)->findAll(); // on recup tt les profils
        foreach ($profils as $testProfil) { // on itere pour trouver le bon profil
            if ($testProfil->getUser() == $user) {
                $profil = $testProfil;
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
                    $seanceProfil->setAttente($posts['attentes_'.$seance->getId()]);
                    $seanceProfil->setLieu($seance->getLieux()[0]);
                    $seanceProfil->isAutorisationPhoto($posts['autorisation_photo']);
                    $seanceProfil->setModePaiement($posts['mode_paiement']);
                    $seanceProfil->setCovoiturage($posts['covoiturage']);
                    $entityManager->persist($seanceProfil);
                }
            }
            $entityManager->flush();

            if($evenement->getURL() != null){
                return  $this->redirect($evenement->getURL());
               
            }else {
                return  $this->redirectToRoute('profil_show', []);
            }
           
            
        }

       
        return $this->render('inscription/WEF.html.twig', [
            'seances'=> $seances,
            'evenement'=>$evenement,
            'seanceByCreneauAndParcours'=> $seanceByCreneauAndParcours,
           
        ]);
        
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


