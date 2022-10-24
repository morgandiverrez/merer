<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\FundBox;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FundBoxFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();
        for ($i = 0; $i < 5; $i++) {
            $fundBox = new FundBox();
            $fundBox->setDescription($this->faker->sentence());
            $fundBox->setLastCountDate($this->faker->dateTime());
            $fundBox->setLocation(($this->getRandomReference('LOCATION')));
            $fundBox->addFundType($this->getRandomReference('FUNDTYPE'));
      

            $this->addReference('FUNDBOX_' . $i, $fundBox);

            $manager->persist($fundBox);
            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [

            LocationFixtures::class,
            FundTypeFixtures::class,


        ];
    }
}
