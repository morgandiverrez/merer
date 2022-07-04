<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Profil;
use App\Entity\Retour;
use App\Entity\Seance;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class RetourFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i<20; $i++){
            $retour = new Retour();

            $retour ->setNoteContenu($faker->numberBetween(1,5));
            $retour->setRemarqueContenu($faker -> sentence());

            $retour->setNoteAnimation($faker->numberBetween(1, 5));
            $retour->setRemarqueAnimation($faker->sentence());

            $retour->setNoteImplication($faker->numberBetween(1, 5));
            $retour->setRemarqueImplication($faker->sentence());

            $retour->setNoteReponseAtente($faker->numberBetween(1, 5));
            $retour->setRemarqueReponseAttente($faker->sentence());

            $retour->setNoteNivCompetence($faker->numberBetween(1, 5));
            $retour->setRemarqueNivCompetence($faker->sentence());

            $retour->setNoteUtilite($faker->numberBetween(1, 5));
            $retour->setRemarqueUtilite($faker->sentence());

            $retour->setNoteGenerale($faker->numberBetween(1, 5));
            $retour->setRemarqueGenerale($faker->sentence());

            $retour->setApportGenerale($faker->sentence());

            $retour->setPlusAimer($faker->sentence());

            $retour->setMoinsAimer($faker->sentence());

            $retour->setAimerVoir($faker->sentence());

            $retour->setMotFin($faker->sentence());

            $profils = $manager->getRepository(Profil::class)->findAll();
            $retour->setProfil($faker->randomElement($profils));

            $seances = $manager->getRepository(Seance::class)->findAll();
            $retour->setSeance($faker->randomElement($seances));
            
            
            
            $manager->persist($retour);
            $manager->flush();
        }
    }
}
