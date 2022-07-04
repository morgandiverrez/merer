<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Formation;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class FormationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i<20; $i++){
            $formation = new Formation();
            $formation ->setCode($faker->regexify('[A-Z]{3}-[0-9]{2}'));
            $formation->setName($faker -> sentence());
            $formation->setDescription($faker->paragraph());
            $formation->setPreRequis($faker->sentence());
            $formation->setPublicCible($faker->sentence());
            $formation->setDuration($faker->dateTime());
            $formation->setOPG($faker->sentence());
            
            
            $manager->persist($formation);
            $manager->flush();
        }
    }
}
