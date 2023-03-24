<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\EquipeElu;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class EquipeEluFixtures extends Fixture
{
    public function load(ObjectManager $entityManager): void
    {
        $this->faker = Factory::create();
        
        for ($i = 0; $i < 20; $i++) {
            $equipeElu = new EquipeElu();
            $equipeElu->setCode($this->faker->regexify('[A-Z]{3}-[0-9]{2}'));
            $equipeElu->setName('EquipeElu' . $i);
            $equipeElu->setCategorie($this->faker->randomElements(['Composante',  'école Ingénieur', 'Centraux', 'CROUS']));
            $equipeElu->setDescription($this->faker->sentence(1));
            $equipeElu->setAdresseMail($this->faker->email());
            $equipeElu->setEtablissement($this->faker->randomElement(['UBO', 'Rennes 1', 'Rennes 2', 'UFR ALLSHS UBO', 'ENIB']));
            $equipeElu->setDateElection($this->faker->dateTime());
            $equipeElu->setDureeMandat($this->faker->numberBetween(1, 3));
            $equipeElu->setFedeFilliere($this->faker->randomElements(['ARES', 'ANEMF', 'AFNEUS', 'BNEI', 'FNAEL', 'UNECD']));
            $this->addReference('EQUIPEELU_'.$i, $equipeElu);


            $entityManager->persist($equipeElu);
           
            $entityManager->flush();
        }
    }
}
