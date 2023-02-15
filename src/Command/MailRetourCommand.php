<?php
//src/Command/MailRetourCommand.php

namespace App\Command;

use DateTime;
use Aws\Ses\SesClient;
use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\TemplatedEmail;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MailRetourCommand extends Command
{
    protected static $defaultName = 'send:email';

    /**
     * @var ContainerInterface
     */
    protected $container;
 
    public function setContainer(ContainerInterface $container): ?ContainerInterface
    {
        $previous = $this->container;
        $this->container = $container;

        return $previous;
    }

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function setEntityManagerInterface(EntityManagerInterface $entityManager): ?EntityManagerInterface
    {
        $previous = $this->entityManager;
        $this->entityManager = $entityManager;

        return $previous;
    }

    /**
     * @var SesClient
     */
    protected $ses;

    public function setSesClient(SesClient $ses): ?SesClient
    {
        $previous = $this->ses;
        $this->ses = $ses;

        return $previous;
    }
    

    public function __construct()
    {
        

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Command for send self email');
    }

    protected function renderView(string $view, array $parameters = []): string
    {
        if (!$this->container->has('twig')) {
            throw new \LogicException('You cannot use the "renderView" method if the Twig Bundle is not available. Try running "composer require symfony/twig-bundle".');
        }

        return $this->container->get('twig')->render($view, $parameters);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Command Send Self Email',
            '============'
        ]);

        $seances = $this->entityManager->getRepository(Seance::class)->findForRetour(new DateTime());

        foreach($seances as $seance){
            foreach($seance->getSeanceProfil() as $seanceProfil){
                $profil = $seanceProfil->getProfil();
                if( $this->entityManager->getRepository(Retour::class)->findBy2ID($seance, $profil) == []){
                    $sender_email = 'no-reply@fedeb.net';
                    $recipient_emails = [$profil->getUser()->getEmail()];
                    $output->writeln('Successful you test');
                    $subject = 'Merer - Retour de formation';
                    $plaintext_body = 'Retour de formation';
                    $char_set = 'UTF-8';
                    $result = $this->ses->sendEmail([
                        'Destination' => [
                            'ToAddresses' => $recipient_emails,
                        ],
                        'ReplyToAddresses' => [$sender_email],
                        'Source' => $sender_email,
                        'Message' => [
                            'Body' => [
                                'Html' => [
                                    'Charset' => $char_set,
                                    'Data' => $this->renderView('emails/retour.html.twig', ["seance" => $seance])
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
                }
            }
        }

        $output->writeln('Successful you send a self email');
    }
}
