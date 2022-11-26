<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Entity\TransactionLine;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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

            foreach($transaction->getTransactionLines() as $transactionLine){
                $entityManager->persist($transactionLine);
            }
            $entityManager->persist($transaction);
            $entityManager->flush();
            
            $transaction = $entityManager->getRepository(Transaction::class)->findTransactionByCode($transaction->getCode());

            $i=0;
            
            foreach($form->get('transactionLines') as $transactionLine){
                $logoUpload = $transactionLine->get('urlProof')->getData();
                if ($logoUpload) {
                    $urlProof = 'transactionLineProof' . $transaction->getTransactionLines()[$i]->getId() . '.' . $logoUpload[0]->guessExtension();
                    $transaction->getTransactionLines()[$i]->setUrlProof('public/build/transactionLine/proof/' . $urlProof);
                    try {
                        $logoUpload[0]->move(
                            'public/build/transactionLine/proof',
                            $urlProof
                        );
                    } catch (FileException $e) {
                    }
                }
                $entityManager->persist($transaction->getTransactionLines()[$i]);
                $i++;
            }
            $entityManager->flush();

            return $this->redirectToRoute('transaction_show', ['transactionId' => $transaction->getId()]);
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

            foreach ($transaction->getTransactionLines() as $transactionLine) {
                $entityManager->persist($transactionLine);
            }
            $entityManager->persist($transaction);
            $entityManager->flush();

            $transaction = $entityManager->getRepository(Transaction::class)->findTransactionByCode($transaction->getCode());

            $i = 0;

            foreach ($form->get('transactionLines') as $transactionLine) {
                $logoUpload = $transactionLine->get('urlProof')->getData();
                if ($logoUpload) {
                    $urlProof = 'transactionLineProof' . $transaction->getTransactionLines()[$i]->getId() . '.' . $logoUpload[0]->guessExtension();
                    $transaction->getTransactionLines()[$i]->setUrlProof('public/build/transactionLine/proof/' . $urlProof);
                    try {
                        $logoUpload[0]->move(
                            'public/build/transactionLine/proof',
                            $urlProof
                        );
                    } catch (FileException $e) {
                    }
                }
                $entityManager->persist($transaction->getTransactionLines()[$i]);
                $i++;
            }
            $entityManager->flush();
            return $this->redirectToRoute('transaction_show', ['transactionId' => $transaction->getId()]);
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

    #[Route('/download/{transactionLineId}', name: 'download')]
    #[IsGranted('ROLE_TRESO')]
    public function download(EntityManagerInterface $entityManager,  $transactionLineId)
    {
        $transactionLine = $entityManager->getRepository(TransactionLine::class)->findById($transactionLineId);

        $finaleFile = $transactionLine->getUrlProof();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($finaleFile) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($finaleFile));
        readfile($finaleFile);

        return $this->redirectToRoute('transaction_show', ['transactionId' => $transactionLine->getTransaction()->getId()]);
    }
}
