<?php

namespace App\DataFixtures;

use Faker\Factory;

use App\Entity\Lieux;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class LieuxFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i<20; $i++){
            $lieux = new Lieux();
            $lieux ->setCode($faker->regexify('[A-Z]{3}-[0-9]{2}'));
            $lieux->setName('Lieux' . $i);
            $lieux->setSalle($faker->regexify('[A-Z]{1}-[0-9]{2}'));
            $lieux->setAdresse($faker->sentence());
            $lieux->setCodePostale($faker->numerify('#####'));
            $lieux->setVille($faker->randomElement(['Brest', 'Quimper', 'Lannion', 'St Brieuc', 'Plouzané', 'Morlaix']));
            
            

            $manager->persist($lieux);
            $manager->flush();
        }
    }
}
