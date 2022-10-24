<?php

namespace App\DataFixtures;

use Faker\Factory;

use App\Entity\User;
use App\Entity\Profil;
use App\Entity\EquipeElu;
use App\Entity\Association;

use App\DataFixtures\EquipeEluFixtures;
use App\DataFixtures\AssociationFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfilFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $hasher; //  erreur apparait si pas bonne version PHP

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    
    public function load(ObjectManager $entityManager): void
    {
        $this->faker = Factory::create();

       
      
        for ($i = 0; $i<20; $i++){
            $profil = new Profil();
            $user = new User();
            $profil ->setCode($this->faker->regexify('[A-Z]{3}-[0-9]{2}'));
            $profil->setLastName($this->faker -> lastName());
            $profil->setName($this->faker -> lastName());
            $profil->setPronom($this->faker->randomElement(['il', 'iel', 'elle']));
            $profil->setDateOfBirth($this->faker->dateTime());
            $profil->setTelephone($this->faker->phoneNumber());
            for($k=0; $k <= $this->faker->numberBetween(0, 3); $k++){
                 $profil->addEquipeElu($this->getRandomReference('EQUIPEELU'));
            }
             for($k=0; $k <= $this->faker->numberBetween(0, 2); $k++){
                 $profil->addAssociation($this->getRandomReference('ASSOCIATION'));
             }
            $this->addReference('PROFIL_'.$i, $profil);


            $user->setEMail($this->faker->email());
            $user->setProfil($profil);
            $user->setRoles($this->faker->randomElements(['ROLE_FORMATEURICE', 'ROLE_BF', 'ROLE_USER'], 2));
            $user->setPassword($this->faker->password());
            $this->addReference('USER_'.$i, $user);

            $entityManager->persist($profil);
            $entityManager->persist($user);
            
        }

        $profil2 = new Profil();
        $user2 = new User();
        $profil2->setCode($this->faker->regexify('[A-Z]{3}-[0-9]{2}'));
        $profil2->setLastName('FORMATION');
        $profil2->setName('VP');
        $profil2->setDateOfBirth($this->faker->dateTime());
        $profil2->setTelephone($this->faker->phoneNumber());
        $this->addReference('PROFIL_2b', $profil2);

        $user2->setEMail('admin@fedeb.net');
        $user2->setProfil($profil2);
        $user2->setRoles(['ROLE_ADMIN']);
        $password = $this->hasher->hashPassword($user, 'password');
        $user2->setPassword($password);
        $this->addReference('USER_2b', $user2);


        $entityManager->persist($profil2);
        $entityManager->persist($user2);


        $profil3 = new Profil();
        $user3 = new User();
        $profil3->setCode($this->faker->regexify('[A-Z]{2}-[0-9]{2}'));
        $profil3->setLastName('DIVERREZ');
        $profil3->setName('Formateur');
        $profil3->setDateOfBirth($this->faker->dateTime());
        $profil3->setTelephone('0651812671');
  
        $this->addReference('PROFIL_3b', $profil3);


        $user3->setEMail('formateurice@fedeb.net');
        $user3->setProfil($profil3);
        $user3->setRoles(['ROLE_FORMATEURICE']);
        $user3->setPassword($password);
        $this->addReference('USER_3b', $user3);

        $entityManager->persist($profil3);
        $entityManager->persist($user3);

        $entityManager->flush();

        $profil4 = new Profil();
        $user4 = new User();
        $profil4->setCode($this->faker->regexify('[A-Z]{2}-[0-9]{2}'));
        $profil4->setLastName('DIVERREZ');
        $profil4->setName('Morgan');
        $profil4->setDateOfBirth($this->faker->dateTime());
        $profil4->setTelephone('0651812671');
       
        $this->addReference('PROFIL_4b', $profil4);


        $user4->setEMail('user@fedeb.net');
        $user4->setProfil($profil4);
        $user4->setRoles(['ROLE_USER']);
        $user4->setPassword($password);
        $this->addReference('USER_4b', $user4);

        $entityManager->persist($profil4);
        $entityManager->persist($user4);


        $profil5 = new Profil();
        $user5 = new User();
        $profil5->setCode($this->faker->regexify('[A-Z]{3}-[0-9]{2}'));
        $profil5->setLastName('DIVERREZ');
        $profil5->setName('BF');
        $profil5->setDateOfBirth($this->faker->dateTime());
        $profil5->setTelephone('0651812671');
      
        $this->addReference('PROFIL_5b', $profil5);

        $user5->setEMail('bf@fedeb.net');
        $user5->setProfil($profil5);
        $user5->setRoles(['ROLE_BF']);
        $user5->setPassword($password);
        $this->addReference('USER_5b', $user5);

        $entityManager->persist($profil5);
        $entityManager->persist($user5);


        $entityManager->flush();

        
    }

    public function getDependencies()
    {
        return [

            AssociationFixtures::class,
            EquipeEluFixtures::class,

        ];
    }
}
