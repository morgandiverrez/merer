<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\Impression;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ImpressionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'choices' => $options['liste_customer'],
            ])
            ->add('name')
            ->add('format', ChoiceType::class, [
                'choices' => [
                    'A3' => 'A3',
                    'A4' =>  'A4',
                    'A5' =>  'A5',
                    'plastification' =>  'plastification',
                ]
            ])
            ->add('rectoVerso')
            ->add('couleur')
            ->add('quantite')
            ->add('factureFinDuMois')
            ->add('dejaPaye')
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Impression::class,
            'liste_customer' => false,
        ]);
        $resolver->setAllowedTypes('liste_customer', 'array');
    }
}
