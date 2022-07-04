<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Badge;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class BadgeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i<20; $i++){
            $badge = new Badge();
            $badge ->setCode($faker->regexify('[A-Z]{3}-[0-9]{2}'));
            $badge->setImage($faker->mimeType());
            $badge->setName('badge' . $i);
            $badge->setCategorie($faker-> randomElement(['wefalors', 'representation', 'gestion projet', 'administratif','gestion equipe']));
            $badge->setDescription($faker->paragraph());
            $badge->setDateCreation($faker->dateTime());
            

            $manager->persist($badge);
            $manager->flush();
           
        }
    }
}
