<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\RepayGrid;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class RepayGridFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();
        for ($i = 0; $i < 20; $i++) {
            $repayGrid = new RepayGrid();
            $repayGrid->setStart($this->faker->word());
            $repayGrid->setEnd($this->faker->word());
            $repayGrid->setTravelMean($this->faker->word());
            $repayGrid->setDistance($this->faker->numberBetween(0.1, 820000));
            $repayGrid->setAmount($this->faker->numberBetween(0.1, 820000));
            $this->addReference('REPAYGRID_' . $i, $repayGrid);

            $manager->persist($repayGrid);
        }
        $manager->flush();
    }
}
