<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Contact;
use App\Entity\Customer;
use App\Entity\Location;
use App\Entity\ChartOfAccounts;
use App\Entity\AdministrativeIdentifier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('impressionAccess')
            ->add('chartOfAccounts', EntityType::class, [
            'class' => ChartOfAccounts::class,
        ])
            ->add('location', EntityType::class, [
            'class' => Location::class,
        ])
            ->add('administrativeIdentifier', EntityType::class, [
            'class' => AdministrativeIdentifier::class,
        ])
            ->add('contacts', EntityType::class, [
            'class' => Contact::class,
            'multiple' => true,
        ])
            ->add('user', EntityType::class, [
            'class' => User::class,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
