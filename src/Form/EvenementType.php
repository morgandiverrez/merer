<?php

namespace App\Form;

use App\Entity\Lieux;
use App\Form\SeanceType;
use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UlidType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
             ->add('name')
            ->add('description', TextAreaType::class)
            ->add( 'dateDebut', DateTimeType::class, [
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
        ])
            ->add( 'dateFin', DateTimeType::class, [
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
        ])
            ->add( 'dateFinInscription', DateTimeType::class, [
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
        ])
            ->add('URL')
            ->add('autorisationPhoto')
            ->add('modePaiement')
            ->add('parcours',RepeatedType::class, [
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
            ])
            ->add('covoiturage')
            ->add('parcoursObligatoire')
            ->add('visible')
            ->add('lieu', EntityType::class, [
                'class'=> Lieux::class,
            ])

            ->add('seances',
                CollectionType::class,
                [
                    'entry_type' => SeanceType::class,
                    "label" => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'entry_options' => [
                        'parcours' => $options['parcours_event'],
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
            'parcours_event' => false,
        ]);

        $resolver->setAllowedTypes('parcours_event', 'array');
    }
}
