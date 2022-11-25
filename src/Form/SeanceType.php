<?php

namespace App\Form;

use App\Entity\Lieux;
use App\Entity\Profil;
use App\Entity\Seance;
use App\Entity\Evenement;
use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            ->add('name')
            
            ->add('datetime', DateTimeType::class,[
             'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            ])
            ->add('nombreplace')
            ->add('formation', EntityType::class, [
            'class' => Formation::class,
            
        ])
            ->add('lieux', EntityType::class, [    
            'class' => Lieux::class,
            'multiple' => true,
      
        ])
            ->add('profil', EntityType::class, [
            'class' => Profil::class,
            'multiple' => true, 
        ])

            ->add('visible')

            ->add('evenement', EntityType::class, [
                'class' => Evenement::class,
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
