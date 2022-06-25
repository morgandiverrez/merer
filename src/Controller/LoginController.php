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
    #[Route('/login', name: 'login')]
    public function login(EntityManagerInterface $entityManager, AuthenticationUtils $authenticationUtils): Response
    {

        $user = $this->getUser();

        // get the login error if there is one
         $error = $authenticationUtils->getLastAuthenticationError();

         // last username entered by the user
         $lastUsername = $authenticationUtils->getLastUsername();

          return $this->render('login/index.html.twig', [
             'last_username' => $lastUsername,
             'user'         => $user,
             'error'         => $error,
        ]);
    }

    #[Route('/logout', name: 'logout', methods: ['GET'])]
    public function logout()
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    // #[Route('/account', name: 'account')]
    // public function account(EntityManagerInterface $entityManager)
    // {
    //     $user = $this->getUser();
    //     // $profil = $entityManager->getRepository(Profil::class)->findByUser($user);
    //     return $this->redirectToRoute('profil_show', ['profilID' => $user->getProfil()->getID()]);
    // }
}
