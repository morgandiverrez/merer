<?php

namespace App\Form\Communication;

use App\Entity\Communication\Group;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description', TextArea::class,[ 'required' =>false,])
            ->add( 'contains', EntityType::class, [
                'class' => Group::class,
                'multiple'=>false,
                'required' => false,                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Group::class,
        ]);
    }
}
