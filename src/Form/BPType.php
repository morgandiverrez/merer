<?php

namespace App\Form;

use App\Entity\BP;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BPType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('exercice')
            ->add('categorie')
            ->add('designation')
            ->add('expectedAmount')
            ->add('reallocateAmount')
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BP::class,
        ]);
    }
}
