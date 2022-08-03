<?php

namespace App\Form;

use App\Entity\Lieux;
use App\Entity\SeanceProfil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class PonctuelleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
           
            ->add('attente')
            ->add('lieu', EntityType::class,[
                'class' => Lieux::class,
               'choices' => $options['liste_lieu'],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SeanceProfil::class,
            'liste_lieu' => false,
        ]);

        $resolver->setAllowedTypes('liste_lieu', 'Doctrine\ORM\PersistentCollection');
    }

}
