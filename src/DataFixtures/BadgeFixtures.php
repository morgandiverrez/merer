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
        $this->faker = Factory::create();

        for ($i = 0; $i<20; $i++){
            $badge = new Badge();
            $badge ->setCode($this->faker->regexify('[A-Z]{3}-[0-9]{2}'));
            $badge->setImage($this->faker->mimeType());
            $badge->setName('badge' . $i);
            $badge->setCategorie($this->faker-> randomElement(['wefalors', 'representation', 'gestion projet', 'administratif','gestion equipe']));
            $badge->setDescription($this->faker->paragraph());
            $badge->setDateCreation($this->faker->dateTime());
            $this->addReference('BADGE_'.$i, $badge);
            
            $manager->persist($badge);
           
        }
        $manager->flush();
 
    }
}
