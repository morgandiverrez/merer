<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Institution;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class InstitutionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $entityManager): void
    {
        $this->faker = Factory::create();
        for ($i = 0; $i < 20; $i++) {
            $institution = new Institution();
            $institution->setHeadquarter($this->faker->boolean());
            $institution->setOpen($this->faker->boolean());

            $institution->setAdministrativeIdentifier($this->getRandomReference('ADMINISTRATIVEIDENTIFIER'));

            $this->addReference('INSTITUTION_' . $i, $institution);


            $entityManager->persist($institution);
        }
        $entityManager->flush();
    }

    public function getDependencies()
    {
        return [

            AdministrativeIdentifierFixtures::class,


        ];
    }
}
