<?php

namespace App\Form\Communication;

use App\Entity\Communication\Post;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add( 'description', TextArea::class, [])
            ->add('visible')
            ->add( 'endDate', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
            ])
            ->add('groups', EntityType::class, [
                'class' => Group::class,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
