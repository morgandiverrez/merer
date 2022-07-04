<?php

namespace App\DataFixtures;

use Faker\Factory;

use App\Entity\User;
use App\Entity\Profil;
use App\Entity\EquipeElu;
use App\Entity\Association;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProfilFixtures extends Fixture
{
    public function load(ObjectManager $entityManager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i<20; $i++){
            $profil = new Profil();
            $user = new User();
            $profil ->setCode($faker->regexify('[A-Z]{3}-[0-9]{2}'));
            $profil->setLastName($faker -> lastName());
            $profil->setName($faker -> name());
            $profil->setPronom($faker->randomElement(['il', 'iel', 'elle']));
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

        for ($i = 0; $i < 20; $i++) {
            $association = new Association();
            $association->setCode($faker->regexify('[A-Z]{3}-[0-9]{2}'));
            $association->setImage($faker->mimeType());
            $association->setName('Association' . $i);
            $association->setSigle($faker->regexify('[A-Z]{6}'));
            $association->setCategorie($faker->randomElement(['wefalors', 'representation', 'gestion projet', 'administratif', 'gestion equipe'], 1));
            $association->setDescription($faker->paragraph());
            $association->setFedeFilliere($faker->randomElement(['ARES', 'ANEMF', 'AFNEUS', 'BNEI', 'FNAEL', 'UNECD']));
            $association->setFedeTerritoire('Fédé B');
            $association->setLocal($faker->regexify('[A-Z]{1}-[0-9]{2}'));
            $association->setAdresseMail($faker->email());
            $association->setDateElection($faker->dateTime());

            $profils = $entityManager->getRepository(Profil::class)->findAll();
            for( $k=0; $k<$faker->numberBetween(0,20) ; $k ){
                $association->addProfil($faker->randomElement($profils));
            }

            $entityManager->persist($association);
            $entityManager->flush();
        }

        for ($i = 0; $i < 20; $i++) {
            $equipeElu = new EquipeElu();
            $equipeElu->setCode($faker->regexify('[A-Z]{3}-[0-9]{2}'));
            $equipeElu->setName('EquipeElu' . $i);
            $equipeElu->setCategorie($faker->randomElement(['wefalors', 'representation', 'gestion projet', 'administratif', 'gestion equipe'], 1));
            $equipeElu->setDescription($faker->sentence(1));
            $equipeElu->setAdresseMail($faker->email());
            $equipeElu->setEtablissement($faker->randomElement(['UBO', 'Rennes 1', 'Rennes 2', 'UFR ALLSHS UBO', 'ENIB']));
            $equipeElu->setDateElection($faker->dateTime());
            $equipeElu->setDureeMandat($faker->numberBetween(1, 3));
            $equipeElu->setFedeFilliere($faker->randomElements(['ARES', 'ANEMF', 'AFNEUS', 'BNEI', 'FNAEL', 'UNECD']));


            $entityManager->persist($equipeElu);
            $profils = $entityManager->getRepository(Profil::class)->findAll();
            for ($k = 0; $k < $faker->numberBetween(0, 20); $k++) {
                $equipeElu->addProfil($faker->randomElement($profils));
            }
            $entityManager->flush();
        }
    }
}
