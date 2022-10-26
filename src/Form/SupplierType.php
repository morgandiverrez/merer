<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\Supplier;
use App\Entity\ChartOfAccounts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupplierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('chartOfAccounts', EntityType::class, [
            'class' => ChartOfAccounts::class,
        ])
            ->add('location', EntityType::class, [
            'class' => Location::class,
        ])
            ->add('administrativeIdentifier', EntityType::class, [
            'class' => AdministrativeIdentifier::class,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Supplier::class,
        ]);
    }
}
