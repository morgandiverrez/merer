<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Profil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
   
    #[Route('/', name: 'index')]
    public function index( ): Response
    {
     

          return $this->redirectToRoute('profil_show');
    }
    #[Route('/login', name: 'login')]
    public function login( AuthenticationUtils $authenticationUtils): Response
    {

      

        // get the login error if there is one
         $error = $authenticationUtils->getLastAuthenticationError();

         // last username entered by the user
         $lastUsername = $authenticationUtils->getLastUsername();

          return $this->render('login/index.html.twig', [
             'last_username' => $lastUsername,
             'error'         => $error,
        ]);
    }

    #[Route('/logout', name: 'logout', methods: ['GET'])]
    public function logout()
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }


    #[Route('/mentionlegale', name: 'mentionlegale')]
    public function mentionlegale()
    {   
        return $this->render('login/mentionlegale.html.twig', [
        ]);
    }

    #[Route('/download/politique_confidentialite', name: 'politique_confidentialite')]
    public function downloadPlanning()
    {
       

        $finaleFile = "build/politique_confidentialite.pdf";

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($finaleFile) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($finaleFile));
        readfile($finaleFile);


        return $this->redirectToRoute('index');
        
    }
}
