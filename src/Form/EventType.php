<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Exercice;
use App\Entity\Location;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('exercice', EntityType::class, [
                'class' => Exercice::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.date', 'DESC');
                },
            ])
            ->add('name')
            ->add('code')
            ->add('amount', MoneyType::class)
            ->add('startDate', DateTimeType::class, [
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
        ])
            ->add('endDate', DateTimeType::class, [
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
        ])
            ->add('description', TextareaType::class)
            ->add('location' ,  EntityType::class, [
            'class' => Location::class,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
