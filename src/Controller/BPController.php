<?php

namespace App\Controller;

use App\Entity\BP;
use App\Form\BPType;
use App\Entity\Exercice;
use App\Entity\TransactionLine;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/bp', name: 'bp_')]
class BPController extends AbstractController
{

    #[Route('/', name: 'showAllBPActual')]
    #[IsGranted('ROLE_TRESO')]
    public function showAllBPActual(): Response
    {
        $date =  date("Y");
        return $this->redirectToRoute('bp_showAll', ['exercice' => $date]);

    }

    #[Route('/exercicePrecedent/{exercice}', name: 'showAllBPPrecedent')]
    #[IsGranted('ROLE_TRESO')]
    public function showAllBPPrecedent( $exercice): Response
    {
        $annee = $exercice-1;
        return $this->redirectToRoute('bp_showAll', ['exercice' => $annee]);
    }

    #[Route('/exerciceSuivant/{exercice}', name: 'showAllBPSuivant')]
    #[IsGranted('ROLE_TRESO')]
    public function showAllBPSuivant( $exercice): Response
    {
  
        $annee = $exercice + 1;
        return $this->redirectToRoute('bp_showAll', ['exercice' => $annee]);
    }


    #[Route('/exercice/{exercice}', name: 'showAll')]
    #[IsGranted('ROLE_TRESO')]
    public function showAll(EntityManagerInterface $entityManager , Request $request, $exercice): Response
    {
        $bpProduits = $entityManager->getRepository(BP::class)->findAllByExerciceProduit($exercice);
        $totalsProduits = [];
        foreach ($bpProduits as $produit) {
            $totalsProduits[$produit->getId()] = $entityManager->getRepository(TransactionLine::class)->totalByBP($produit)['total'];
        }

        $bpCharges = $entityManager->getRepository(BP::class)->findAllByExerciceCharge($exercice);
        $totalsCharges = [];
        foreach ($bpCharges as $charge) {
            $totalsCharges[$charge->getId()] = $entityManager->getRepository(TransactionLine::class)->totalByBP($charge)['total'];
        }

        


       
        return $this->render('bp/showAll.html.twig', [
            'bpProduits' => $bpProduits,
            'bpCharges' => $bpCharges,
            'totalsProduits' => $totalsProduits,
            'totalsCharges' => $totalsCharges,
            'exercice' => $exercice,
        ]);
    }

    #[Route('/show/{bpID}', name: 'show')]
    #[IsGranted('ROLE_TRESO')]
    public function show(EntityManagerInterface $entityManager, $bpID): Response
    {
        $bp = $entityManager->getRepository(BP::class)->findById($bpID)[0];
        $total = $entityManager->getRepository(TransactionLine::class)->totalByBP($bp)['total'];

        $totalsTransactions = [];
        foreach ($bp->getTransactions() as $transaction) {
            $totalsTransactions[$transaction->getId()] = $entityManager->getRepository(TransactionLine::class)->totalByTransaction($transaction->getId())['total'];
        }
        return $this->render('bp/show.html.twig', [
            'bp' => $bp,
            'totalsTransactions' => $totalsTransactions,
            'total' => $total,

        ]);
    }

    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_TRESO')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $exercice = $entityManager->getRepository(Exercice::class)->findOneByAnnee(intval(date('Y')));

        if (!$exercice) {
            $exercice = new Exercice();
            $exercice->setAnnee(intval(date('Y')));
            $entityManager->persist($exercice);
            $entityManager->flush();
        }
        $bp = new BP();
        $form = $this->createForm(BPType::class, $bp);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bp);
            $entityManager->flush();
            return $this->redirectToRoute('bp_show', ['bpID' => $bp->getId()]);
        }

        return $this->render('bp/new.html.twig', [
            'bp' => $bp,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{bpID}', name: 'edit')]
    #[IsGranted('ROLE_TRESO')]
    public function edit(EntityManagerInterface $entityManager, Request $request, $bpID): Response
    {
        $exercice = $entityManager->getRepository(Exercice::class)->findOneByAnnee(intval(date('Y')));

        if (!$exercice) {
            $exercice = new Exercice();
            $exercice->setAnnee(intval(date('Y')));
            $entityManager->persist($exercice);
            $entityManager->flush();
        }
        $bp = $entityManager->getRepository(BP::class)->findById($bpID)[0];
        $form = $this->createForm(BPType::class, $bp);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($bp);
            $entityManager->flush();
            return $this->redirectToRoute('bp_show', ['bpID' => $bpID]);
        }

        return $this->render('bp/edit.html.twig', [
            'bp' => $bp,
            'form' => $form->createView(),

        ]);
    }

    #[Route('/delete/{bpID}', name: 'delete')]
    #[IsGranted('ROLE_TRESO')]
    public function delete(EntityManagerInterface $entityManager, $bpID): Response
    {

        $bp = $entityManager->getRepository(BP::class)->findById($bpID)[0];
        $entityManager->remove($bp);
        $entityManager->flush();

        return $this->redirectToRoute('bp_showAllBPActual');
    }
}
