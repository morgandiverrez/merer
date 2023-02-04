<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\Supplier;
use App\Entity\ChartOfAccounts;
use App\Entity\AdministrativeIdentifier;
use Symfony\Component\Form\AbstractType;
use App\Form\AdministrativeIdentifierType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SupplierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
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
