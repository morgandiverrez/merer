<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Profil;
use Symfony\Component\Mime\Email;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Aws\Ses\SesClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SesClient $ses): Response
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


        
         
        // Replace sender@example.com with your "From" address.
        // This address must be verified with Amazon SES.
        $sender_email = 'no-reply@fedeb.net';

        // Replace these sample addresses with the addresses of your recipients. If
        // your account is still in the sandbox, these addresses must be verified.
        $recipient_emails = [$form->get('email')->getData()];

        $subject = 'Comfirmation inscription Merer';
        $plaintext_body = 'Comfirmation' ;
        $html_body =  '<h1>Cette email comfirme votre inscription sur la plateforme de gestion Merer de la Fédé B</h1>'.
                    '<p>Lien pour vous connectez à la plateforme Merer <a href="http://15.236.191.187/">'.
                    'Merer - Fédé B</p>';
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
                    'Data' => $html_body,
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

            return $this->redirectToRoute('login', []);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'user '            => $user,
        ]);
    }
}      