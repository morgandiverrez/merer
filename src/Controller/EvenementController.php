<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/evenement', name: 'evenement_')]
class EvenementController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    #[IsGranted('ROLE_BF')]
    public function showAll(EntityManagerInterface$entityManager, Request $request): Response
    {
        $evenements = $entityManager->getRepository(Evenement::class)->findAllOrderByDate();
        if ($request->isMethod('post')) {
            $posts = $request->request->all();
            if ($posts['name']) {
                $evenements = array_intersect($evenements, $entityManager->getRepository(Evenement::class)->findAllByName($posts['name']));
            }
            if ($posts['description']) {
                $evenements = array_intersect($evenements, $entityManager->getRepository(Evenement::class)->findAllByDescription($posts['description']));
            }
        
           
        }
        return $this->render('evenement/showAll.html.twig', [
            'evenements' => $evenements,

        ]);
    }

    #[Route('/show/{evenementID}', name: 'show')]
    #[IsGranted('ROLE_USER')]
    public function show(EntityManagerInterface $entityManager, $evenementID): Response
    {
        $seanceByCreneauAndParcours = [];

        $evenement = $entityManager->getRepository(Evenement::class)->findById($evenementID)[0];
        
        $seances = $evenement->getSeances(); //on recup tt les seances qui ont un groupe qui commence par la variable groupe

        foreach ($seances as $seance) {
            if ($seance->getParcours() != null) {
                $seanceByCreneauAndParcours[strval($seance->getDatetime()->format("d/m/Y H:i"))][$seance->getParcours()] = $seance;
            } else { //si pas de parcours (donc formation pour tt les parcours si plusieur parcours)
                foreach ($evenement->getParcours() as $parcours) { // on itere les parcours pour les remplir tous de cette seance
                    $seanceByCreneauAndParcours[$seance->getDatetime()->format("d/m/Y H:i")][$parcours] = $seance;
                }
            }
        }

        

        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
            'seanceByCreneauAndParcours'=> $seanceByCreneauAndParcours,
        ]);
    }

    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_BF')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $evenement = new Evenement();
        $parcours =  [];
        foreach ($evenement->getParcours() as $parcour) {
            $parcours[$parcour] = $parcour;
        }
        $form = $this->createForm(EvenementType::class, $evenement, ['parcours_event' => $parcours]);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            if ($evenement->getDateFinInscription() == null) {
                $evenement->setDateFinInscription($evenement->getDateFin());
            }
            foreach($evenement->getSeances() as $seance){
                $seance->setVisible($evenement->isVisible());
                $entityManager->persist($seance);
            }
            $entityManager->persist($evenement);
            $entityManager->flush();
            return $this->redirectToRoute('evenement_show', ['evenementID' => $evenement->getId()]);
        }

        return $this->render('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),

        ]);
    }



    #[Route('/edit/{evenementID}', name: 'edit')]
    #[IsGranted('ROLE_BF')]
    public function edit(EntityManagerInterface $entityManager, Request $request, $evenementID): Response
    {
        $evenement = $entityManager->getRepository(Evenement::class)->findById($evenementID)[0];
        $parcours =  [];
        foreach($evenement->getParcours() as $parcour) {
            $parcours[$parcour] = $parcour;
        }
        $form = $this->createForm(EvenementType::class, $evenement,['parcours_event' => $parcours] );
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            if($evenement->getDateFinInscription() == null){
                $evenement->setDateFinInscription($evenement->getDateFin());
            }
            foreach ($evenement->getSeances() as $seance) {
                $seance->setVisible($evenement->isVisible());
                $entityManager->persist($seance);
            }
           
            $entityManager->persist($evenement);
            $entityManager->flush();
            return $this->redirectToRoute('evenement_show', ['evenementID' => $evenement->getId()]);
        }

        return $this->render('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),

        ]);
    }



    #[Route('/delete/{evenementID}', name: 'delete')]
    #[IsGranted('ROLE_FORMA')]
    public function delete(EntityManagerInterface $entityManager, $evenementID): Response
    {

        $evenement = $entityManager->getRepository(Evenement::class)->findById($evenementID)[0];
        $entityManager->remove($evenement);
        $entityManager->flush();

        return $this->redirectToRoute('evenement_showAll', []);
    }

    #[Route('/visible/{evenementID}', name: 'cloture')]
    #[IsGranted('ROLE_FORMA')]
    public function visible(EntityManagerInterface $entityManager, $evenementID): Response
    {
        $evenement = $entityManager->getRepository(Evenement::class)->findById($evenementID)[0];
        $evenement->setVisible(true);
        $entityManager->persist($evenement);
        $entityManager->flush();
        return $this->redirectToRoute('evenement_showAll');
    }

    #[Route('/unvisible/{evenementID}', name: 'uncloture')]
    #[IsGranted('ROLE_FORMA')]
    public function unvisible(EntityManagerInterface $entityManager, $evenementID): Response
    {
        $evenement = $entityManager->getRepository(Evenement::class)->findById($evenementID)[0];
        $evenement->setVisible(false);
        $entityManager->persist($evenement);
        $entityManager->flush();
        return $this->redirectToRoute('evenement_showAll');
    }
}
