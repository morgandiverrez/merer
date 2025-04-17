<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Profil;
use Symfony\Component\Mime\Email;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $user = new User();
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

            $entityManager->persist($user);
            $entityManager->flush();

            $email = (new Email())
                        ->from('no-reply@fedeb.net')
                        ->to($user->getEmail())
                        //->cc('formation@example.com')
                        //->bcc('bcc@example.com')
                        //->replyTo('fabien@example.com')
                        //->priority(Email::PRIORITY_HIGH)
                        ->subject('Time for Symfony Mailer!')
                        ->text('Sending emails is fun again!');
            $mailer->send($email);

            return $this->redirectToRoute('login', []);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'user '            => $user,
        ]);
    }

    #[Route('/mail', name: 'email')]
    public function mail(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        //$user = $this->getUser();

        $email = (new Email())
            //->from('no-reply@fedeb.net')
            ->to('morgan.diverrez@fedeb.net')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!');
            
        $mailer->send($email);
           
            return $this->redirectToRoute('register');

        

    }
}
