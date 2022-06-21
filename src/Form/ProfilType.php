<?php

namespace App\Form;

use App\Entity\Profil;
use App\Entity\EquipeElu;
use App\Entity\Association;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('last_name')
            ->add('pronom')
                
            ->add('date_of_birth', BirthdayType::class)
            ->add('equipeElu', EntityType::class,[
                 // looks for choices from this entity
                 'class' => EquipeElu::class,
                 // uses the EquipeElu.name property as the visible option string
                
                 // used to render a select box, check boxes or radios
                 'multiple' => true,
                 'expanded' => true,
            ])

            ->add('association', EntityType::class, [
                 'class' => Association::class,
                 'multiple' => true,
                 'expanded'=> true,
                
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profil::class,
        ]);
    }
}
