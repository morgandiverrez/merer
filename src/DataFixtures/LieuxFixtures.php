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
        $this->faker = Factory::create();

        for ($i = 0; $i<20; $i++){
            $lieux = new Lieux();
            $lieux ->setCode($this->faker->regexify('[A-Z]{3}-[0-9]{2}'));
            $lieux->setName('Lieux' . $i);
            $lieux->setSalle($this->faker->regexify('[A-Z]{1}-[0-9]{2}'));
            $lieux->setAdresse($this->faker->sentence());
            $lieux->setCodePostale($this->faker->numerify('#####'));
            $lieux->setVille($this->faker->randomElement(['Brest', 'Quimper', 'Lannion', 'St Brieuc', 'Plouzané', 'Morlaix']));
            $this->addReference('LIEU_'.$i, $lieux);

            $manager->persist($lieux);
            $manager->flush();
        }
    }
}
