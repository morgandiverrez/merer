<?php

namespace App\DataFixtures;

use DateInterval;
use Faker\Factory;
use App\Entity\Evenement;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class EvenementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $this->faker = Factory::create();
        
        for ($k=0; $k<=5; $k++){
            $evenement = new Evenement();
            $evenement->setName($this->faker->word());
            $evenement->setURL($this->faker->url());
            $evenement->setDescription($this->faker->sentence());
            $evenement->setDateDebut($this->faker->dateTimeBetween('-1 month', '+1 month'));
           
            $evenement->setDateFin(date_add($evenement->getDateDebut() , date_interval_create_from_date_string("3 day")));
            $this->addReference('EVENEMENT_' . $k, $evenement);

            $manager->persist($evenement);
        }
        
        $manager->flush();
    }
}
