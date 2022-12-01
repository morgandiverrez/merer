<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\Institution;
use Doctrine\DBAL\Types\BooleanType;
use App\Entity\AdministrativeIdentifier;
use Symfony\Component\Form\AbstractType;
use App\Form\AdministrativeIdentifierType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class InstitutionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('headquarter')
            ->add('open')
            ->add('administrativeIdentifier', EntityType::class, [
                'class' => AdministrativeIdentifier::class,
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Institution::class,
        ]);
    }
}
