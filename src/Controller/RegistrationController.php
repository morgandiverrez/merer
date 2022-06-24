<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Profil;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $profil = new Profil();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setProfil($profil);
            $entityManager->persist($user);
            $entityManager->flush();
            

            return $this->redirectToRoute('profil_edit', ['profilID' => $profil->getID()]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'user '            => $user,
        ]);
    }

    // #[Route('/register/role_admin', name: 'role_admin')]
    // public function ajoutRole(EntityManagerInterface $entityManager)
    // {

    //     $role = ['ROLE_ADMIN'];


    //     $user = $this->getUser();
    //     $user->setRoles($role);


    //     $entityManager->persist($user);
    //     $entityManager->flush();

    //     return $this->redirectToRoute('profil_showAll');
    // }
}
