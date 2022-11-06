<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Event;
use Faker\Factory;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();
        for ($i = 0; $i < 20; $i++) {
            $event = new Event();
            $event->setName($this->faker->word());
            $event->setCode($this->faker->numerify('####'));
            $event->setStartDate($this->faker->dateTimeBetween('-6 month', '+6 month'));
            $event->setEndDate(date_add($event->getStartDate() , date_interval_create_from_date_string("3 day")));
            $event->setDescription($this->faker->sentence());
             $event->setExercice($this->getRandomReference('EXERCICE'));
            $event->setLocation($this->getRandomReference('LOCATION'));
            $this->addReference('EVENT_' . $i, $event);

            $manager->persist($event);
          
        }
        $manager->flush();
    }
    
    public function getDependencies()
    {
        return [

            ExerciceFixtures::class,
            LocationFixtures::class,
           
        ];
    }
}
