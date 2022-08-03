<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Retour;
use App\Entity\SeanceProfil;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\SeanceProfilFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RetourFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();
       

        for ($i = 0; $i<20; $i++){
            $retour = new Retour();

            $seanceProfil = $this->getRandomReference('SEANCEPROFIL');

            $seance = $seanceProfil->getSeance();
            $retour->setSeance($seance); 
            $profil = $seanceProfil->getProfil();    
            $retour->setProfil($profil);


            $profil->addBadge($seance->getFormation()->getBadge());

            $retour ->setNoteContenu($this->faker->numberBetween(1,5));
            $retour->setRemarqueContenu($this->faker -> sentence());

            $retour->setNoteAnimation($this->faker->numberBetween(1, 5));
            $retour->setRemarqueAnimation($this->faker->sentence());

            $retour->setNoteImplication($this->faker->numberBetween(1, 5));
            $retour->setRemarqueImplication($this->faker->sentence());

            $retour->setNoteReponseAtente($this->faker->numberBetween(1, 5));
            $retour->setRemarqueReponseAttente($this->faker->sentence());

            $retour->setNoteNivCompetence($this->faker->numberBetween(1, 5));
            $retour->setRemarqueNivCompetence($this->faker->sentence());

            $retour->setNoteUtilite($this->faker->numberBetween(1, 5));
            $retour->setRemarqueUtilite($this->faker->sentence());

            $retour->setNoteGenerale($this->faker->numberBetween(1, 5));
            $retour->setRemarqueGenerale($this->faker->sentence());

            $retour->setApportGenerale($this->faker->sentence());

            $retour->setPlusAimer($this->faker->sentence());

            $retour->setMoinsAimer($this->faker->sentence());

            $retour->setAimerVoir($this->faker->sentence());

            $retour->setMotFin($this->faker->sentence());


            $this->addReference('RETOUR_'.$i, $retour);
            
            $manager->persist($retour);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [

            SeanceProfilFixtures::class,
            

        ];
    }
}
