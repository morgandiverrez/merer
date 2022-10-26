<?php

namespace App\Controller;

use DateTime;
use App\Entity\FundBox;
use App\Entity\FundType;
use App\Form\FundBoxType;
use App\Entity\FundTypeFundBox;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#[Route('/fundBox', name: 'fundBox_')]
class FundBoxController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    #[IsGranted('ROLE_TRESO')]
    public function showAll(EntityManagerInterface $entityManager, Request $request): Response
    {
        $fundBox = $entityManager->getRepository(FundBox::class)->findAll();

        $totals = [];
        foreach ($fundBox as $box) {
            $totals[$box->getName()] = $entityManager->getRepository(FundBox::class)->montantTotale($box->getId())[0]['total_amount'];
            if ($totals[$box->getName()] == null) $totals[$box->getName()] = 0;
        }
        return $this->render('fundBox/showAll.html.twig', [
            'fundBox' => $fundBox,
            'totals'=>$totals,

        ]);
    }



    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_TRESO')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $fundBox = new FundBox();
        $form = $this->createForm(FundBoxType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $types = ['onecent', "twocent", "fivecent", "tencent", 'twentycent', 'fiftycent', 'oneeuro', 'twoeuro', 'fiveeuro', 'teneuro', 'twentyeuro', "fiftyeuro", "hundredeuro", "twohundredeuro", "fivehundredeuro" ];
            $fundTypes = $entityManager->getRepository(FundType::class)->findAllInOrder();
            $i=0;
            foreach($types as $type){
                $fundTypeJoin = new FundTypeFundBox();
                $fundTypeJoin->setFundBox($fundBox);
                $fundTypeJoin->setFundType($fundTypes[$i]);
                $data = $form->get($type)->getData();
                if(! $data) $data = 0;
                $fundTypeJoin->setQuantity($data);
                $entityManager->persist($fundTypeJoin);
                
                $i++;
            }
            $fundBox->setName($form->get("name")->getData());
            $fundBox->setDescription($form->get("description")->getData());
            $fundBox->setChartOfAccounts($form->get("chartOfAccounts")->getData());
            $fundBox->setLocation($form->get("location")->getData());
            $fundBox->setLastCountDate(new DateTime());

            $entityManager->persist($fundBox);
            $entityManager->flush();
            return $this->redirectToRoute('fundBox_show', ['fundBoxID' => $fundBox->getId()]);
        }

        return $this->render('fundBox/new.html.twig', [
            'fundBox' => $fundBox,
            'form' => $form->createView(),


        ]);
    }



    #[Route('/edit/{fundBoxID}', name: 'edit')]
    #[IsGranted('ROLE_TRESO')]
    public function edit(EntityManagerInterface $entityManager, Request $request, $fundBoxID): Response
    {
        $fundBox = $entityManager->getRepository(FundBox::class)->findById($fundBoxID)[0];
        $form = $this->createForm(FundBoxType::class);
        $form->get('name')->setData($fundBox->getName());
        $form->get('description')->setData($fundBox->getDescription());
        $form->get('chartOfAccounts')->setData($fundBox->getChartOfAccounts());
        $form->get('location')->setData($fundBox->getLocation());

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $types = ['onecent', "twocent", "fivecent", "tencent", 'twentycent', 'fiftycent', 'oneeuro', 'twoeuro', 'fiveeuro', 'teneuro', 'twentyeuro', "fiftyeuro", "hundredeuro", "twohundredeuro", "fivehundredeuro"];
            $fundTypes = $entityManager->getRepository(FundType::class)->findAllInOrder();
            $i = 0;
            foreach ($types as $type) {
                $fundTypeJoin = $entityManager->getRepository(FundTypeFundBox::class)->findBy2ID($fundTypes[$i]->getId(), $fundBox->getId())[0];
                $data = $form->get($type)->getData();
                if (!$data) $data = 0;
                $fundTypeJoin->setQuantity($data);
                $entityManager->persist($fundTypeJoin);

                $i++;
            }
            $fundBox->setName($form->get("name")->getData());
            $fundBox->setDescription($form->get("description")->getData());
            $fundBox->setChartOfAccounts($form->get("chartOfAccounts")->getData());
            $fundBox->setLocation($form->get("location")->getData());
            $fundBox->setLastCountDate(new DateTime());

            $entityManager->persist($fundBox);
            $entityManager->flush();
            return $this->redirectToRoute('fundBox_show', ['fundBoxID' => $fundBoxID]);
        }

        return $this->render('fundBox/edit.html.twig', [
            'fundBox' => $fundBox,
            'form' => $form->createView(),

        ]);
    }

    #[Route('/show/{fundBoxID}', name: 'show')]
    #[IsGranted('ROLE_TRESO')]
    public function show(EntityManagerInterface $entityManager,  $fundBoxID): Response
    {
        $fundBox = $entityManager->getRepository(FundBox::class)->findById($fundBoxID)[0];

        $total = $entityManager->getRepository(FundBox::class)->montantTotale($fundBox->getId())[0]['total_amount'];
        if ($total == null) $total = 0;

        return $this->render('fundBox/show.html.twig', [
            'fundBox' => $fundBox,
            'total' => $total,

        ]);
    }

    
}
