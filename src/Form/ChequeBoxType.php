<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\ChequeBox;
use App\Entity\ChartOfAccounts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ChequeBoxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description', TextareaType::class)
            ->add('lastCountDate',DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('chartOfAccounts', EntityType::class, [
                'class' => ChartOfAccounts::class,
        ])
        ->add('location', EntityType::class, [
            'class' => Location::class,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChequeBox::class,
        ]);
    }
}
