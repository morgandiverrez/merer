<?php

namespace App\DataFixtures;

use Faker\Factory;

use App\Entity\Profil;
use App\Entity\Seance;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class SeanceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i<50; $i++){
            $seance = new Seance();

            $seance ->setCode($faker->regexify('[A-Z]{3}-[0-9]{2}'));
            $seance->setName('seance' . $i);
            $seance->setDatetime($faker->dateTime());
            $seance->setNombrePlace($faker->numberBetween(5, 25));

            $profils = $manager->getRepository(Profil::class)->findAll();
            for ($k = 0; $k < 2; $k++) {
                $seance->addProfil($faker->randomElement($profils));
            }

            $lieux = $manager->getRepository(Lieux::class)->findAll();
            for ($k = 0; $k < $faker->numberBetween(1,2); $k++ ) {
                $seance->addLieux($faker->randomElement($lieux));
            }

            $formations = $manager->getRepository(Formation::class)->findAll();
            $seance->setFormation($faker->randomElement($formations));
            

            $manager->persist($seance);
            $manager->flush();
        }
    }
}
