<?php

namespace App\Form;

use App\Entity\InvoiceLine;
use App\Entity\CatalogService;
use App\Entity\CatalogDiscount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class InvoiceLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('discount', MoneyType::class,[
                'required' => false
            ])
            ->add('quote', TextAreaType::class, [
                'required' => false,
            ])
            ->add('catalogDiscount',  EntityType::class, [
            'class' => CatalogDiscount::class,
            'required' => false,
        ])
            ->add('catalogService',  EntityType::class, [
            'class' => CatalogService::class,
            'required' => false,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InvoiceLine::class,
        ]);
    }
}
