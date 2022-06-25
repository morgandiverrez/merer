<?php

namespace App\Form;

use App\Entity\Seance;
use App\Entity\Formation;
use App\Entity\Lieux;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code')
            ->add('name')
            ->add('datetime')
            ->add('nombreplace')
            ->add('formation', EntityType::class, [
            'class' => Formation::class,
            'multiple' => true,
            'expanded' => true
        ])
            ->add('lieux', EntityType::class, [    
            'class' => Lieux::class,
            'multiple' => true,
            'expanded' => true
        ])
            ->add('profil', EntityType::class, [
            'class' => Profil::class,
            'multiple' => true,
            'expanded' => true,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seance::class,
        ]);
    }
}
