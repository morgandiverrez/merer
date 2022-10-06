<?php

namespace App\Controller;

use DateTime;
use App\Entity\Impression;
use App\Form\ImpressionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/impression', name: 'impression_')]
class ImpressionController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    #[IsGranted('ROLE_BF')]
    public function showAll(EntityManagerInterface $entityManager, Request $request): Response
    {
        $impressions = $entityManager->getRepository(Impression::class)->findAll();

        if ($request->isMethod('post')) {
            $posts = $request->request->all();
            if ($posts['association']) {
                $impressions = array_intersect($impressions, $entityManager->getRepository(Impression::class)->findAllByAssociation($posts['association']));
            }
            if ($posts['format']) {
                $impressions = array_intersect($impressions, $entityManager->getRepository(Impression::class)->findAllByFormat($posts['format']));
            }
        }

        return $this->render('impression/showAll.html.twig', [
            'impressions' => $impressions,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(EntityManagerInterface $entityManager, Request $request): Response
    {
        $impression = new Impression();
        $form = $this->createForm(ImpressionType::class, $impression);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $impression->setDatetime(new DateTime());
            $entityManager->persist($impression);
            $entityManager->flush();
            return $this->redirectToRoute('impression_validation', []);
        }

        return $this->render('impression/new.html.twig', [
            'impression' => $impression,
            'form' => $form->createView(),

        ]);
    }

    #[Route('/validation', name: 'validation')]
    public function validation(EntityManagerInterface $entityManager, Request $request): Response
    {
        return $this->render('impression/validation.html.twig', [
        ]);
    }

    #[Route('/delete/{impressionId}', name: 'delete')]
    #[IsGranted('ROLE_BF')]
    public function delete(EntityManagerInterface $entityManager, $impressionId): Response
    {

        $impression = $entityManager->getRepository(Impression::class)->findById($impressionId)[0];
        $entityManager->remove($impression);
        $entityManager->flush();

        return $this->redirectToRoute('impression_showAll', []);
    }
}
