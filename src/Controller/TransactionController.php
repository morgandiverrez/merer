<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\TransactionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/transaction', name: 'transaction_')]
class TransactionController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    #[IsGranted('ROLE_TRESO')]
    public function showAll(EntityManagerInterface $entityManager): Response
    {
        $transactions = $entityManager->getRepository(Transaction::class)->findAll();
        return $this->render('transaction/showAll.html.twig', [
            'transactions' => $transactions,
          
        ]);
    }

    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_TRESO')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $transaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (isset($entityManager->getRepository(Transaction::class)->findMaxDayTransaction(date("Ymd") * 100)[0])){
                $nbtransaction = $entityManager->getRepository(Transaction::class)->findMaxDayTransaction(date("Ymd") * 100)[0]['code'];
                $transaction->setCode($nbtransaction + 1);
            }else{
                $nbtransaction = 0;    
                 $transaction->setCode(date("Ymd") * 100 + $nbtransaction + 1);
            }
            $entityManager->persist($transaction);
            $entityManager->flush();
            return $this->redirectToRoute('transactionLine_new', ['transactionId' => $transaction->getId()]);
        }

        return $this->render('transaction/new.html.twig', [
            'transaction' => $transaction,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{transactionId}', name: 'edit')]
    #[IsGranted('ROLE_TRESO')]
    public function edit(EntityManagerInterface $entityManager, $transactionId, Request $request): Response
    {
        $transaction = $entityManager->getRepository(Transaction::class)->findTransactionById($transactionId);
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $entityManager->persist($transaction);
            $entityManager->flush();
            return $this->redirectToRoute('transactionLine_new', ['transactionId' => $transaction->getId()]);
        }

        return $this->render('transaction/edit.html.twig', [
            'transaction' => $transaction,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/show/{transactionId}', name: 'show')]
    #[IsGranted('ROLE_TRESO')]
    public function show(EntityManagerInterface $entityManager, $transactionId): Response
    {
        $transaction = $entityManager->getRepository(Transaction::class)->findTransactionById($transactionId);
        return $this->render('transaction/show.html.twig', [
            'transaction' => $transaction,
        ]);
    }
}
