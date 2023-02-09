<?php

namespace App\Form;

use App\Entity\Invoice;
use App\Entity\Customer;
use App\Entity\Exercice;
use App\Entity\Transaction;
use App\Form\InvoiceLineType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add( 'customer',  EntityType::class, [
            'class' => Customer::class,
        ])
            ->add('exercice', EntityType::class, [
                'class' => Exercice::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.annee', 'DESC');
                },
            ])
            ->add('credit')
            ->add('invoiceLines',
                CollectionType::class,
                [
                    'entry_type' => InvoiceLineType::class,
                    'label' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]
            )
          
           
         
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}
