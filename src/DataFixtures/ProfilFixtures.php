<?php

namespace App\DataFixtures;

use Faker\Factory;

use App\Entity\User;
use App\Entity\Profil;
use App\Entity\EquipeElu;
use App\Entity\Association;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfilFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    
    public function load(ObjectManager $entityManager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i<20; $i++){
            $profil = new Profil();
            $user = new User();
            $profil ->setCode($faker->regexify('[A-Z]{3}-[0-9]{2}'));
            $profil->setLastName($faker -> lastName());
            $profil->setName($faker -> lastname());
            $profil->setPronom($faker->randomElements(['il', 'iel', 'elle']));
            $profil->setDateOfBirth($faker->dateTime());
            $profil->setScore($faker->numberBetween(0, 400));
            $profil->setTelephone($faker->phoneNumber());
            

            $user->setEMail($faker->email());
            $user->setProfil($profil);
            $user->setRoles($faker->randomElements(['ROLE_FORMATEURICE', 'ROLE_BF', 'ROLE_USER'], 2));
            $user->setPassword($faker->password());


            $entityManager->persist($profil);
            $entityManager->persist($user);
            
            $entityManager->flush();
        }

        $profil2 = new Profil();
        $user2 = new User();
        $profil2->setCode($faker->regexify('[A-Z]{3}-[0-9]{2}'));
        $profil2->setLastName('FORMATION');
        $profil2->setName('VP');
        $profil2->setPronom($faker->randomElements(['il', 'iel', 'elle']));
        $profil2->setDateOfBirth($faker->dateTime());
        $profil2->setScore($faker->numberBetween(0, 400));
        $profil2->setTelephone($faker->phoneNumber());


        $user2->setEMail('admin@fedeb.net');
        $user2->setProfil($profil2);
        $user2->setRoles(['ROLE_ADMIN']);
        $password = $this->hasher->hashPassword($user, 'password');
        $user2->setPassword($password);


        $entityManager->persist($profil2);
        $entityManager->persist($user2);

        $profil6 = new Profil();
        $user6 = new User();
        $profil6->setCode($faker->regexify('[A-Z]{3}-[0-9]{2}'));
        $profil6->setLastName('DIVERREZ');
        $profil6->setName('Morgan');
        $profil6->setPronom(['il']);
        $profil6->setDateOfBirth($faker->dateTime());
        $profil6->setScore($faker->numberBetween(0, 400));
        $profil6->setTelephone('0651812671');


        $user6->setEMail('bf@fedeb.net');
        $user6->setProfil($profil6);
        $user6->setRoles(['ROLE_BF']);
        $user6->setPassword($password);


        $entityManager->persist($profil6);
        $entityManager->persist($user6);

        $entityManager->flush();

        $profil3 = new Profil();
        $user3 = new User();
        $profil3->setCode($faker->regexify('[A-Z]{2}-[0-9]{2}'));
        $profil3->setLastName('DIVERREZ');
        $profil3->setName('Morgan');
        $profil3->setPronom(['il']);
        $profil3->setDateOfBirth($faker->dateTime());
        $profil3->setScore($faker->numberBetween(0, 400));
        $profil3->setTelephone('0651812671');


        $user3->setEMail('formateurice@fedeb.net');
        $user3->setProfil($profil3);
        $user3->setRoles(['ROLE_FORMATEURICE']);
        $user3->setPassword($password);


        $entityManager->persist($profil3);
        $entityManager->persist($user3);

        $entityManager->flush();

        $profil4 = new Profil();
        $user4 = new User();
        $profil4->setCode($faker->regexify('[A-Z]{2}-[0-9]{2}'));
        $profil4->setLastName('DIVERREZ');
        $profil4->setName('Morgan');
        $profil4->setPronom(['il']);
        $profil4->setDateOfBirth($faker->dateTime());
        $profil4->setScore($faker->numberBetween(0, 400));
        $profil4->setTelephone('0651812671');


        $user4->setEMail('user@fedeb.net');
        $user4->setProfil($profil4);
        $user4->setRoles(['ROLE_USER']);
        $user4->setPassword($password);


        $entityManager->persist($profil4);
        $entityManager->persist($user4);

        $entityManager->flush();

        for ($i = 0; $i < 20; $i++) {
            $association = new Association();
            $association->setCode($faker->regexify('[A-Z]{3}-[0-9]{2}'));
            $association->setImage($faker->mimeType());
            $association->setName('Association' . $i);
            $association->setSigle($faker->regexify('[A-Z]{6}'));
            $association->setCategorie($faker->randomElement(['wefalors', 'representation', 'gestion projet', 'administratif', 'gestion equipe']));
            $association->setDescription($faker->paragraph());
            $association->setFedeFilliere($faker->randomElement(['ARES', 'ANEMF', 'AFNEUS', 'BNEI', 'FNAEL', 'UNECD']));
            $association->setFedeTerritoire('Fédé B');
            $association->setLocal($faker->regexify('[A-Z]{1}-[0-9]{2}'));
            $association->setAdresseMail($faker->email());
            $association->setDateElection($faker->dateTime());

            $profils = $entityManager->getRepository(Profil::class)->findAll();
            for( $k=0; $k<$faker->numberBetween(0,2) ; $k ){
                $association->addProfil($faker->randomElement($profils));
            }

            $entityManager->persist($association);
            $entityManager->flush();
        }



        for ($i = 0; $i < 20; $i++) {
            $equipeElu = new EquipeElu();
            $equipeElu->setCode($faker->regexify('[A-Z]{3}-[0-9]{2}'));
            $equipeElu->setName('EquipeElu' . $i);
            $equipeElu->setCategorie($faker->randomElements(['wefalors', 'representation', 'gestion projet', 'administratif', 'gestion equipe']));
            $equipeElu->setDescription($faker->sentence(1));
            $equipeElu->setAdresseMail($faker->email());
            $equipeElu->setEtablissement($faker->randomElement(['UBO', 'Rennes 1', 'Rennes 2', 'UFR ALLSHS UBO', 'ENIB']));
            $equipeElu->setDateElection($faker->dateTime());
            $equipeElu->setDureeMandat($faker->numberBetween(1, 3));
            $equipeElu->setFedeFilliere($faker->randomElements(['ARES', 'ANEMF', 'AFNEUS', 'BNEI', 'FNAEL', 'UNECD']));


            $entityManager->persist($equipeElu);
            $profils = $entityManager->getRepository(Profil::class)->findAll();
            for ($k = 0; $k < $faker->numberBetween(0, 3); $k++) {
                $equipeElu->addProfil($faker->randomElement($profils));
            }
            $entityManager->flush();
        }
    }
}
