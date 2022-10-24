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

            if ($transactionLine->getChartOfAccounts()->getCode() == 51216) {
                $transactionLineBis = new TransactionLine();
                $transactionLineBis->setLabel("HelloAsso " . $transactionLine->getDate()->format('m'));
                $transactionLineBis->setDate($transactionLine->getDate());
                $transactionLineBis->setAmount($transactionLine->getAmount());
                $transactionLineBis->setChartofaccounts($entityManager->getRepository(Chartofaccounts::class)->findChartofaccountsByCode(51216));
                $transactionLineBis->setTransaction($transaction);

                $entityManager->persist($transactionLineBis);
                $entityManager->flush();

                $transactionLineBis = new TransactionLine();
                $transactionLineBis->setLabel("Paiement Séjour 2021");
                $transactionLineBis->setDate($transactionLine->getDate());
                $transactionLineBis->setAmount($transactionLine->getAmount());
                $transactionLineBis->setChartofaccounts($entityManager->getRepository(Chartofaccounts::class)->findChartofaccountsByCode(70600));
                $transactionLineBis->setTransaction($transaction);

                $entityManager->persist($transactionLineBis);
                $entityManager->flush();

                $transactionLineBis = new TransactionLine();
                $transactionLineBis->setLabel("HelloAsso");
                $transactionLineBis->setDate($transactionLine->getDate());
                $transactionLineBis->setAmount(-$transactionLine->getAmount());
                $transactionLines = $transaction->gettransactionLines();
                foreach ($transactionLines as $value) {
                    if ($value->getChartOfAccounts()->getCode() > 41110000 && $value->getChartOfAccounts()->getCode() < 41120000) {
                        $transactionLineBis->setChartofaccounts($value->getChartOfAccounts());
                        break;
                    }
                }
                $transactionLineBis->setTransaction($transaction);

                $entityManager->persist($transactionLineBis);
                $entityManager->flush();



                return $this->redirectToRoute('transaction_show', ['transactionId' => $transactionId]);
            }

            if ($transactionLine->getChartOfAccounts()->getCode() == 51212) {
                $transactionLineBis = new TransactionLine();
                $transactionLineBis->setLabel($transactionLine->getLabel());
                $transactionLineBis->setDate($transactionLine->getDate());
                $transactionLineBis->setAmount($transactionLine->getAmount());
                $transactionLineBis->setChartofaccounts($entityManager->getRepository(Chartofaccounts::class)->findChartofaccountsByCode(51212));
                $transactionLineBis->setTransaction($transaction);

                $entityManager->persist($transactionLineBis);
                $entityManager->flush();

                $transactionLineBis = new TransactionLine();
                $transactionLineBis->setLabel("Paiement Séjour 2021");
                $transactionLineBis->setDate($transactionLine->getDate());
                $transactionLineBis->setAmount($transactionLine->getAmount());
                $transactionLineBis->setChartofaccounts($entityManager->getRepository(Chartofaccounts::class)->findChartofaccountsByCode(70600));
                $transactionLineBis->setTransaction($transaction);

                $entityManager->persist($transactionLineBis);
                $entityManager->flush();

                $transactionLineBis = new TransactionLine();
                $transactionLineBis->setLabel("Virement Bancaire");
                $transactionLineBis->setDate($transactionLine->getDate());
                $transactionLineBis->setAmount(-$transactionLine->getAmount());
                $transactionLines = $transaction->gettransactionLines();
                foreach ($transactionLines as $value) {
                    if ($value->getChartOfAccounts()->getCode() > 41110000 && $value->getChartOfAccounts()->getCode() < 41120000) {
                        $transactionLineBis->setChartofaccounts($value->getChartOfAccounts());
                        break;
                    }
                }
                $transactionLineBis->setTransaction($transaction);

                $entityManager->persist($transactionLineBis);
                $entityManager->flush();

                return $this->redirectToRoute('transaction_show', ['transactionId' => $transactionId]);
            }

            if ($transactionLine->getChartOfAccounts()->getCode() == 70600) {

                $transactionLineBis = new TransactionLine();
                $transactionLineBis->setLabel("Paiement Séjour 2021");
                $transactionLineBis->setDate($transactionLine->getDate());
                $transactionLineBis->setAmount($transactionLine->getAmount());
                $transactionLineBis->setChartofaccounts($entityManager->getRepository(Chartofaccounts::class)->findChartofaccountsByCode(70600));
                $transactionLineBis->setTransaction($transaction);

                $entityManager->persist($transactionLineBis);
                $entityManager->flush();

                $transactionLineBis = new TransactionLine();
                $transactionLineBis->setLabel("Chèque");
                $transactionLineBis->setDate($transactionLine->getDate());
                $transactionLineBis->setAmount(-$transactionLine->getAmount());
                $transactionLines = $transaction->gettransactionLines();
                foreach ($transactionLines as $value) {
                    if ($value->getChartOfAccounts()->getCode() > 41110000 && $value->getChartOfAccounts()->getCode() < 41120000) {
                        $transactionLineBis->setChartofaccounts($value->getChartOfAccounts());
                        break;
                    }
                }
                $transactionLineBis->setTransaction($transaction);

                $entityManager->persist($transactionLineBis);
                $entityManager->flush();

                return $this->redirectToRoute('transaction_show', ['transactionId' => $transactionId]);
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
        $transactionLine = $entityManager->getRepository(TransactionLine::class)->findTransactionLineById($transactionLineId);
        $form = $this->createForm(TransactionLineType::class, $transactionLine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($transactionLine);
            $entityManager->flush();
            return $this->redirectToRoute('transaction_show', ['transactionId' => $transactionId]);
        }

        return $this->render('transactionLine/edit.html.twig', [
            'transaction' => $transactionLine,
            'form' => $form->createView(),
        ]);
    }

}
