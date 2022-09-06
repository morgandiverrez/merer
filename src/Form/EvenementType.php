<?php

namespace App\Form;

use App\Entity\Lieux;
use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
             ->add('name')
            ->add('description')
            ->add('dateDebut')
            ->add('dateFin')
            ->add('dateFinInscription')
            ->add('URL')
            ->add('autorisationPhoto')
            ->add('modePaiement')
            ->add('covoiturage')
            ->add('parcoursObligatoire')
            ->add('visible')
            ->add('lieu', EntityType::class, [
                'class'=> Lieux::class,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
