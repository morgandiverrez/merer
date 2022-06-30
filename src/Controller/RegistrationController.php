<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Profil;
use Symfony\Component\Mime\Email;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
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

            $email = (new Email())
                ->from('no-reply@fedeb.net')
                ->to($user -> getEmail())
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Inscription sur la plateforme de gestion des formations de la Fédé B')
                
                ->htmlTemplate('emails/signup.html.twig')
                // pass variables (name => value) to the template
                ->context([
                    'expiration_date' => new \DateTime('+7 days'),
                    'username' => 'foo',
                ])

            $mailer->send($email);


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
