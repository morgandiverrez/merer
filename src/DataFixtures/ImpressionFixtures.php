<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Impression;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ImpressionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();
        for ($i = 0; $i < 20; $i++) {
            $impression = new Impression();
            $impression->setDatetime($this->faker->dateTime());
            $impression->setAssociation($this->getRandomReference('ASSOCIATION'));
            $impression->setName($this->faker->lastName());
            $impression->setFormat($this->faker->randomElement(['A3', 'A4', 'A5', 'plastification']));
            $impression->setRectoVerso($this->faker->boolean());
            $impression->setCouleur($this->faker->boolean());
            $impression->setQuantite($this->faker->numberBetween(1, 100));
            $impression->setFactureFinDuMois($this->faker->boolean());
            $impression->setDejaPaye($this->faker->boolean());


            $this->addReference('IMPRESSION_' . $i, $impression);

            $manager->persist($impression);
            $manager->flush();
        }
 
    }


    public function getDependencies()
    {
        return [

            AssociationFixtures::class,


        ];
    }
}
