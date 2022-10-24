<?php

namespace App\Controller;

use DateTime;
use App\Entity\Profil;
use App\Entity\Seance;
use App\Entity\Evenement;
use App\Entity\SeanceProfil;
use App\Form\PonctuelleType;
use App\Form\InscriptionType;
use App\Form\SeanceProfilType;
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
        $seance = $entityManager->getRepository(Seance::class)->findByID($seanceID)[0];
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

        // on verifie que la seance est sensé etre visible (sinon, on renvoi vers le profil de l'utilisateur)
        if (! $seance->isVisible()) {
            return $this->redirectToRoute('profil_show', []);
        }

        //on verifie que cette seance n'est pas dans un evenement (si oui, on renvoie vers le formulaire approprié)
        if( null != $seance->getEvenement()){
            return $this->redirectToRoute('inscription_evenement', ['evenementID' => $seance->getEvenement()->getId()]);
        }

        //on verifie qu'il reste des places
        
        if( $seance->getNombrePlace() <= $entityManager->getRepository(SeanceProfil::class)->CountBySeance($seance)[0][1]){
            return $this->render('inscription/noPlace.html.twig');
        }

        if($seance->getDatetime() <= date('d/m/y H:i:s')){
            return $this->render('inscription/close.html.twig');
        }
        

        $seanceProfil = new SeanceProfil();
        $form = $this->createForm(InscriptionType::class, $seanceProfil, [ 'liste_lieu' => $seance->getLieux()]);
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
    public function evenement( EntityManagerInterface $entityManager, Request $request, $evenementID): Response
    {
        $seanceByCreneauAndParcours = [];
        $restePlace =[];
        $evenement = $entityManager->getRepository(Evenement::class)->findById($evenementID)[0];
        //on verifie que l'evenement est visible
        if ( ! $evenement->isVisible()) {
            return $this->redirectToRoute('profil_show', []);
        }
         // on verifie qu'il reste des places
        if (  $entityManager->getRepository(SeanceProfil::class)->CountByEvenement($evenement)[0][1] >= $evenement->getNombrePlace()) {
            return $this->render('inscription/noPlace.html.twig');
        }

        //on verifie que les inscriptions ne sont pas cloturé
        if ($evenement->getDateFinInscription() <= date('y/m/d H:i:s')) {
            return $this->render('inscription/close.html.twig');
        }

        $seances = $evenement->getSeance(); //on recup tt les seances qui ont un groupe qui commence par la variable groupe

       

        foreach($seances as $seance){   
            //rangé par parcours et horaire
            if( $seance->getParcours() != null ){ 
                $seanceByCreneauAndParcours[strval($seance->getDatetime()->format("d/m/Y H:i"))][$seance->getParcours()] = $seance;
            }else{ //si pas de parcours (donc formation pour tt les parcours si plusieur parcours)
                foreach($evenement->getParcours() as $parcours){ // on itere les parcours pour les remplir tous de cette seance
                    $seanceByCreneauAndParcours[$seance->getDatetime()->format("d/m/Y H:i")][$parcours] = $seance;
                }
            }

            // verifié s'il reste des places
            if ($entityManager->getRepository(SeanceProfil::class)->CountBySeance($seance) < $seance->getNombrePlace() ) {
                $restePlace[$seance->getId()] = false;
            }else{
                $restePlace[$seance->getId()] = true;
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
            if($evenement->isParcoursObligatoire()){
                 $parcoursSeance = $entityManager->getRepository(Seance::class)->findAllByParcours($evenement, $posts['inscription']);
                 foreach( $parcoursSeance as $seance){
                    $seanceProfil = new SeanceProfil();
                    $seanceProfil->setSeance($seance);
                    $seanceProfil->setProfil($profil);
                    $seanceProfil->setHorrodateur(new DateTime());
                    $seanceProfil->setAttente($posts['attentes_' . $seance->getId()]);
                    $seanceProfil->setLieu($evenement->getLieu());
                    if($evenement->isAutorisationPhoto()){
                        $seanceProfil->isAutorisationPhoto($posts['autorisation_photo']);
                    }
                    if($evenement->getModePaiement() != []){
                        $seanceProfil->setModePaiement($posts['mode_paiement']);
                    }
                    if($evenement->isCovoiturage()){
                        $seanceProfil->setCovoiturage($posts['participation_covoiturage']);
                    }
                    $entityManager->persist($seanceProfil);
                 }
            }else{
                foreach ($seances as $seance) {
                    if (isset($posts['inscription_' . $seance->getDatetime()->format('d/m/y H:i:s')])  and $posts['inscription_' . $seance->getDatetime()->format('d/m/y H:i:s')] == $seance->getId()) {
                        $seanceProfil = new SeanceProfil();
                        $seanceProfil->setSeance($seance);
                        $seanceProfil->setProfil($profil);
                        $seanceProfil->setHorrodateur(new DateTime());
                        $seanceProfil->setAttente($posts['attentes_'.$seance->getId()]);
                        $seanceProfil->setLieu($seance->getEvenement()->getLieu());
                        $seanceProfil->isAutorisationPhoto($posts['autorisation_photo']);
                        $seanceProfil->setModePaiement($posts['mode_paiement']);
                        $seanceProfil->setCovoiturage($posts['participation_covoiturage']);
                        $entityManager->persist($seanceProfil);
                    }
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
           'restePlace'=> $restePlace,
        ]);
        
    }
   

    #[Route('/delete/{seanceID}/{profilID}', name: 'delete')]
    #[IsGranted('ROLE_FORMA')]
    public function delete(EntityManagerInterface $entityManager, $seanceID, $profilID): Response
    {
        
        $seanceProfil = $entityManager->getRepository(SeanceProfil::class)->findBy2Id($seanceID, $profilID)[0];
        $entityManager->remove($seanceProfil);
        $entityManager->flush();

        return $this->redirectToRoute('seance_liste_inscrit', ['seanceID' => $seanceID]);
    }
} 


