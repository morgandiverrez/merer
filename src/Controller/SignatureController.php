<?php

namespace App\Controller;


use App\Entity\Profil;
use App\Entity\Association;
use App\Form\AssociationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


#[Route('/signature', name: 'signature_')]

class AssociationController extends AbstractController
{
    #[Route('/CROUS', name: 'CROUS')]
    #[IsGranted('ROLE_USER')]
    public function CROUS(): Response
    {
        return $this->render('signature/CROUS.html.twig');
    }

    #[Route('/centrauxUBO', name: 'centrauxUBO')]
    #[IsGranted('ROLE_USER')]
    public function centrauxUBO(): Response
    {
        return $this->render('signature/centrauxUBO.html.twig');
    }
}