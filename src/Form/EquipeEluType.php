<?php

namespace App\Form;

use App\Entity\EquipeElu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EquipeEluType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code')
            ->add('name')
            ->add('categorie', ChoiceType::class, [
                'choices' => [
                    'UBO' => 'UBO',
                    'UFR' =>  'UFR',
                    'Rennes 1' =>  'Rennes 1',
                    'Rennes 2' =>    'Rennes 2',
                    'CROUS'=> 'CROUS',
                    'Centraux' =>  'Centraux',
                    'Ecole Ingénieur' =>  'Ecole Ingénieur',
                ],
                'multiple' => true,
            ])
            ->add('description' , TextareaType::class)
            ->add('adresse_mail', EmailType::class)
            ->add('etablissement')
            ->add('date_election')
            -> add('duree_mandat')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EquipeElu::class,
        ]);
    }
}
