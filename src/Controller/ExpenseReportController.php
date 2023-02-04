<?php

namespace App\Controller;

use DateTime;
use App\Entity\Customer;
use App\Entity\Exercice;
use Aws\Ses\SesClient;
use App\Entity\Transaction;
use App\Controller\CustomerController;
use App\Entity\ExpenseReport;
use App\Form\TransactionType;
use App\Entity\TransactionLine;
use App\Form\ExpenseReportType;
use App\Entity\ExpenseReportLine;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


#[Route('/expenseReport', name: 'expenseReport_')]
class ExpenseReportController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    #[IsGranted('ROLE_TRESO')]
    public function showAll(EntityManagerInterface $entityManager, Request $request): Response
    {
        $expenseReports = $entityManager->getRepository(ExpenseReport::class)->findAll();
        if ($request->isMethod('post')) {
            $posts = $request->request->all();
            if ($posts['demandeur']) {
                $expenseReports = array_intersect($expenseReports, $entityManager->getRepository(ExpenseReport::class)->findAllByDemandeur($posts['demandeur']));
            }
            if ($posts['comfirmer'] == '1' ) {
                $expenseReports = array_intersect($expenseReports, $entityManager->getRepository(ExpenseReport::class)->findAllComfirm());
            }
            if ($posts['comfirmer'] == '0') {
                $expenseReports = array_intersect($expenseReports, $entityManager->getRepository(ExpenseReport::class)->findAllNotComfirm());
            }
        }
        return $this->render('expense_report/showAll.html.twig', [
            'expenseReports' => $expenseReports,

        ]);
    }

    #[Route('/delete/{expenseReportID}', name: 'delete')]
    #[IsGranted('ROLE_USER')]
    public function delete(EntityManagerInterface $entityManager, $expenseReportID): Response
    {

        $expenseReport = $entityManager->getRepository(ExpenseReport::class)->findById($expenseReportID)[0];
        $customers = $entityManager->getRepository(Customer::class)->findAll();
        $i = 0;
        while (!isset($customer) and isset($customers[$i])) {
            if ($customers[$i]->getUser() == $this->getUser()) {
                $customer = $customers[$i];;
            }
            $i++;
        }


        if (($customer == $expenseReport->getCustomer() or $this->isGranted("ROLE_TRESO")) and !$expenseReport->isComfirm()) {
            $entityManager->remove($expenseReport);
            $entityManager->flush();
            if($this->isGranted("ROLE_TRESO")){
                return $this->redirectToRoute('expenseReport_showAll', []);
            }
        } else {
            return $this->redirectToRoute('account');
        }
    }

    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_USER')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $exercice = $entityManager->getRepository(Exercice::class)->findOneByAnnee(intval(date('Y')));
        $user = $this->getUser();
        
        if (!$exercice) {
            $exercice = new Exercice();
            $exercice->setAnnee(intval(date('Y')));
            $entityManager->persist($exercice);
            $entityManager->flush();
        }
        $expenseReport = new ExpenseReport();
        $form = $this->createForm(ExpenseReportType::class, $expenseReport);
        $form->handleRequest($request);


        $customer = $this->getUser()->getCustomer();
        if( ! $customer){
           $customer = (new CustomerController)->newCustomerForUser(  $entityManager, $user);
        }

        if ($form->isSubmitted() && $form->isValid()) {
      
            $expenseReport->setDate( new DateTime());
            $expenseReport->setCustomer($customer);
            if (isset($entityManager->getRepository(ExpenseReport::class)->findMaxDayExpenseReport(date("Ymd") * 100)[0])) {
                $nbtransaction = $entityManager->getRepository(ExpenseReport::class)->findMaxDayExpenseReport(date("Ymd") * 100)[0]['code'];
                $expenseReport->setCode($nbtransaction + 1);
            } else {
                $nbtransaction = 0;
                $expenseReport->setCode(date("Ymd") * 100 + $nbtransaction + 1);
            }
            foreach ($expenseReport->getExpenseReportLines() as $expenseReportLine) {
                $entityManager->persist($expenseReportLine);
            }
           
            foreach ($expenseReport->getExpenseReportRouteLines() as $expenseReportRouteLine) {
                if($expenseReportRouteLine->getDistance() <= 50){
                    $expenseReportRouteLine->setAmount($expenseReportRouteLine->getDistance()*0.15);
                }else{
                     $expenseReportRouteLine->setAmount($expenseReportRouteLine->getDistance()*0.20);
                }
                $entityManager->persist($expenseReportRouteLine);

            }

            $entityManager->persist($expenseReport);
            $entityManager->persist($customer);
            $entityManager->flush();

            $expenseReport = $entityManager->getRepository(ExpenseReport::class)->findExpenseReportByCode($expenseReport->getCode());

            $i = 0;
            
            foreach ($form->get('expenseReportLines') as $expenseReportLine) {
                $logoUpload = $expenseReportLine->get('document')->getData();
                if ($logoUpload) {
                    $document = 'expenseReportLineProof_' .  $expenseReport->getExpenseReportLines()[$i]->getId() . '.' . $logoUpload[0]->guessExtension();
                    $patho =  "build/expenseReport/".$expenseReport->getCode()."/";
                    $path =$patho.$document;
                 
                    
                    if (glob ($path)) {
                    $pathOld = $patho."old/".$document;
                        rename($path, $pathOld);
                    }
                    
                    $expenseReport->getExpenseReportLines()[$i]->setDocument($path);
                    try {
                        $logoUpload[0]->move(
                            $patho,
                            $document
                        );
                    } catch (FileException $e) {
                    }
                }
                
                $entityManager->persist($expenseReport->getExpenseReportLines()[$i]);
                $i++;
            }
            $entityManager->flush();
            if($customer->getBankDetails()[0]){
                if($customer->getBankDetails()[0]->getIBAN() and $customer->getBankDetails()[0]->getBIC()){
                    return $this->redirectToRoute('expenseReport_show', ['expenseReportID' => $expenseReport->getId()]);
                }
            }
            return $this->redirectToRoute('bankDetail_new', []);
        }

        return $this->render('expense_report/new.html.twig', [
            'expenseReport' => $expenseReport,
            'form' => $form->createView(),
        ]);
       
    }

    #[Route('/edit/{expenseReportID}', name: 'edit')]
    #[IsGranted('ROLE_USER')]
    public function edit(EntityManagerInterface $entityManager, $expenseReportID, Request $request): Response
    {
        $exercice = $entityManager->getRepository(Exercice::class)->findOneByAnnee(intval(date('Y')));

        if (!$exercice) {
            $exercice = new Exercice();
            $exercice->setAnnee(intval(date('Y')));
            $entityManager->persist($exercice);
            $entityManager->flush();
        }
        $expenseReport = $entityManager->getRepository(ExpenseReport::class)->findExpenseReportById($expenseReportID);
        $user = $this->getUser();
        $customer = $user->getCustomer();
       

        
        if (($customer == $expenseReport->getCustomer() or $this->isGranted("ROLE_TRESO")) and !$expenseReport->isComfirm()) {
            
            $form = $this->createForm(ExpenseReportType::class, $expenseReport);
            $form->handleRequest($request);


            if ($form->isSubmitted() && $form->isValid()) {
               
                $expenseReport->setDate(new DateTime());
                $expenseReport->setCustomer($customer);
               
                foreach ($expenseReport->getExpenseReportLines() as $expenseReportLine) {
                    $entityManager->persist($expenseReportLine);
                }

                foreach ($expenseReport->getExpenseReportRouteLines() as $expenseReportRouteLine) {
                    if ($expenseReportRouteLine->getDistance() <= 50) {
                        $expenseReportRouteLine->setAmount($expenseReportRouteLine->getDistance() * 0.15);
                    } else {
                        $expenseReportRouteLine->setAmount($expenseReportRouteLine->getDistance() * 0.20);
                    }
                    $entityManager->persist($expenseReportRouteLine);
                }

                $entityManager->persist($expenseReport);
                $entityManager->flush();

                $expenseReport = $entityManager->getRepository(ExpenseReport::class)->findExpenseReportByCode($expenseReport->getCode());

                $i = 0;

                foreach ($form->get('expenseReportLines') as $expenseReportLine) {
                    $logoUpload = $expenseReportLine->get('document')->getData();
                    
                    if ($logoUpload) {
                    
                        //on créer le nouveau chemin du nouveau doc
                        $generalPath =  "build/expenseReport/".$expenseReport->getCode()."/";
                        $newDocument = 'expenseReportLineProof_' .  $expenseReport->getExpenseReportLines()[$i]->getId() . '.' . $logoUpload[0]->guessExtension();
                        $newPath =$generalPath.$newDocument;

                        //on recupere l'extension du doc à déplacer 
                        $oldPathOldDocument = $expenseReport->getExpenseReportLines()[$i]->getDocument();
                        $element = explode(".", $oldPathOldDocument);
                        $oldExtension = end($element);

                        //verif si le dossier old existe
                        if (!is_dir($generalPath.'old')) {
                            mkdir($generalPath.'old/');
                        }

                        if(glob($generalPath.'old/oldExpenseReportLineProof_'. $expenseReport->getExpenseReportLines()[$i]->getId()."_*")){
                            $listOldProof = glob($generalPath.'old/oldExpenseReportLineProof_'. $expenseReport->getExpenseReportLines()[$i]->getId()."_*");
                            $LastProof =end($listOldProof);
                            $listPartPathLastProof = explode("_", $LastProof);
                            $strNumberLastProof = end($listPartPathLastProof);
                            $intNumberLastProof = intval($strNumberLastProof);
                            $oldPath =$generalPath.'old/oldExpenseReportLineProof_'. $expenseReport->getExpenseReportLines()[$i]->getId()."_".$intNumberLastProof+1 . '.'.$oldExtension;
                            rename($newPath, $oldPath);
                        }else{
                            $oldPath =$generalPath.'old/oldExpenseReportLineProof_'. $expenseReport->getExpenseReportLines()[$i]->getId()."_1".'.'.$oldExtension;
                            rename($newPath, $oldPath);
                        }
                    
                        
                        //on stock le nouveau chemin 
                        $expenseReport->getExpenseReportLines()[$i]->setDocument($newPath);
                        try {
                            $logoUpload[0]->move(
                                $generalPath,
                                $newDocument
                            );
                        } catch (FileException $e) {
                        }
                    }
                    $entityManager->persist($expenseReport->getExpenseReportLines()[$i]);
                    $i++;
                }
                $entityManager->flush();

                if($customer->getBankDetails()[0]){
                    if($customer->getBankDetails()[0]->getIBAN() and $customer->getBankDetails()[0]->getBIC()){
                        return $this->redirectToRoute('expenseReport_show', ['expenseReportID' => $expenseReport->getId()]);
                    }
                }
                return $this->redirectToRoute('bankDetail_new', []);
            }

            return $this->render('expense_report/edit.html.twig', [
                'expenseReport' => $expenseReport,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('account');
        }
    }

    #[Route('/show/{expenseReportID}', name: 'show')]
    #[IsGranted('ROLE_USER')]
    public function show(EntityManagerInterface $entityManager, $expenseReportID): Response
    {
         $user = $this->getUser();
        $customer = $user->getCustomer();
        $expenseReport = $entityManager->getRepository(ExpenseReport::class)->findExpenseReportById($expenseReportID);
        if ($customer == $expenseReport->getCustomer() or $this->isGranted("ROLE_TRESO")) {
        return $this->render('expense_report/show.html.twig', [
            'expenseReport' => $expenseReport,
        ]);
        } else {
            return $this->redirectToRoute('account');
        }
    }

    

    #[Route('/comfirm/{expenseReportID}', name: 'comfirm')]
    #[IsGranted('ROLE_TRESO')]
    public function comfirm(EntityManagerInterface $entityManager, Request $request, $expenseReportID, SesClient $ses): Response
    {
        $expenseReport = $entityManager->getRepository(ExpenseReport::class)->findById($expenseReportID)[0];

        $expenseReport->setComfirm(true);
       

        $transaction = new Transaction();
        $transaction->setExercice($expenseReport->getExercice());
        $expenseReport->setTransaction($transaction);
        if (isset($entityManager->getRepository(Transaction::class)->findMaxDayTransaction(date("Ymd") * 100)[0]['code'])) {
            $nbtransaction = $entityManager->getRepository(Transaction::class)->findMaxDayTransaction(date("Ymd") * 100)[0]['code'];
        } else {
            $nbtransaction = 0;
        }
        $transaction->setCode(intval(date("Ymd")) * 100 + $nbtransaction + 1);
        $transaction->setClosure(false);


        $transactionline = new TransactionLine();
        $transactionline->setTransaction($transaction);
        $transactionline->setDate(new \DateTime());
        $transactionline->setAmount(ExpenseReportController::expenseReportTotale($expenseReport));
        $transactionline->setLabel("NDF-" . $expenseReport->getCode());

        $entityManager->persist($transaction);
        $entityManager->persist($transactionline);
        $entityManager->persist($expenseReport);
        $entityManager->flush();

                $sender_email = 'no-reply@fedeb.net';
        $recipient_emails = [$expenseReport->getCustomer()->getUser()->getEmail()];

        $subject = 'Merer - Confirmation NDF';
        $plaintext_body = 'Confirmation NDF' ;
        $char_set = 'UTF-8';
        $result = $ses->sendEmail([
            'Destination' => [
                'ToAddresses' => $recipient_emails,
            ],
            'ReplyToAddresses' => [$sender_email],
            'Source' => $sender_email,
            'Message' => [
                'Body' => [
                    'Html' => [
                        'Charset' => $char_set,
                        'Data' =>$this->renderView('emails/confirm_ndf.html.twig',["expenseReport" => $expenseReport])
                    ],
                    'Text' => [
                        'Charset' => $char_set,
                        'Data' => $plaintext_body,
                    ],
                ],
                'Subject' => [
                    'Charset' => $char_set,
                    'Data' => $subject,
                ],
            ],
        ]);

        return $this->redirectToRoute('expenseReport_showAll');
    }

    public function expenseReportTotale($expenseReport)
    {
        $nbTrajet = count($expenseReport->getExpenseReportRouteLines());
        $nbFrais = count($expenseReport->getExpenseReportLines());
        $totale = 0;

        for ($i = 0; $i < $nbTrajet-1; $i++) {

            if ($expenseReport->getExpenseReportRouteLines()[$i]->getAmount() != null and  $expenseReport->getExpenseReportRouteLines()[$i]->getRepayGrid()->getAmount() == null ){  
                $add = $expenseReport->getExpenseReportRouteLines()[$i]->getAmount();
            } else if($expenseReport->getExpenseReportRouteLines()[$i]->getAmount() == null and  $expenseReport->getExpenseReportRouteLines()[$i]->getRepayGrid()->getAmount() != null){
                $add = $expenseReport->getExpenseReportRouteLines()[$i]->getRepayGrid()->getAmount();
            } else {
                $add = 0;
            }

            $totale += $add;
            
        }

        for ($i = 0; $i < $nbFrais; $i++) {
            if ($expenseReport->getExpenseReportLines()[$i]->getAmount()) {
                $totale += $expenseReport->getExpenseReportLines()[$i]->getAmount();
            }
            
        }

        return $totale;
    }

}

