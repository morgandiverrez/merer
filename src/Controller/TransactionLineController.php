<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Entity\TransactionLine;
use App\Form\TransactionLineType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


#[Route('/transactionLine', name: 'transactionLine_')]
class TransactionLineController extends AbstractController
{

    #[Route('/new/{transactionId}', name: 'new')]
    #[IsGranted('ROLE_TRESO')]
    public function new(EntityManagerInterface $entityManager, $transactionId, Request $request): Response
    {
        $transactionLine = new TransactionLine();
        $form = $this->createForm(TransactionLineType::class, $transactionLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $transaction = $entityManager->getRepository(Transaction::class)->findTransactionById($transactionId);

            $logoUpload = $form->get('urlProof')->getData();
            if ($logoUpload) {
                $urlProof = 'transactionLineProof' . $transactionLine->getId() . '.' . $logoUpload[0]->guessExtension();


                $transactionLine->setUrlProof('public/build/transactionLine/proof/' . $urlProof);
                try {
                    $logoUpload[0]->move(
                        'public/build/transactionLine/proof',
                        $urlProof
                    );
                } catch (FileException $e) {
                }
            }
            $transactionLine->setTransaction($transaction);
            $entityManager->persist($transactionLine);
            $entityManager->flush();

                return $this->redirectToRoute('transaction_show', ['transactionId' => $transactionId]);
        }

         

        return $this->render('transactionLine/edit.html.twig', [
            'transaction' => $transactionLine,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/edit/{transactionId}/{transactionLineId}', name: 'edit')]
    #[IsGranted('ROLE_TRESO')]
    public function edit(EntityManagerInterface $entityManager, $transactionId, $transactionLineId, Request $request): Response
    {
        $transactionLine = $entityManager->getRepository(TransactionLine::class)->findById($transactionLineId);
        $form = $this->createForm(TransactionLineType::class, $transactionLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $transaction = $entityManager->getRepository(Transaction::class)->findTransactionById($transactionId);

            $logoUpload = $form->get('urlProof')->getData();
            if ($logoUpload) {
                $urlProof = 'transactionLineProof' . $transactionLine->getId() . '.' . $logoUpload[0]->guessExtension();


                $transactionLine->setUrlProof('public/build/transactionLine/proof/' . $urlProof);
                try {
                    $logoUpload[0]->move(
                        'public/build/transactionLine/proof',
                        $urlProof
                    );
                } catch (FileException $e) {
                }
            }
            $transactionLine->setTransaction($transaction);
            $entityManager->persist($transactionLine);
            $entityManager->flush();
            return $this->redirectToRoute('transaction_show', ['transactionId' => $transactionId]);
        }

        return $this->render('transactionLine/edit.html.twig', [
            'transaction' => $transactionLine,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{transactionLineID}', name: 'delete')]
    #[IsGranted('ROLE_TRESO')]
    public function delete(EntityManagerInterface $entityManager, $transactionLineID): Response
    {

        $transactionLine = $entityManager->getRepository(TransactionLine::class)->findById($transactionLineID);
        $entityManager->remove($transactionLine);
        $entityManager->flush();


        return $this->redirectToRoute('transaction_show', ['transactionId'=>$transactionLine->getTransaction()->getId()]);
    }
}
