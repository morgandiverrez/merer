<?php

namespace App\Form;

use App\Entity\ExpenseReport;
use Doctrine\ORM\EntityRepository;
use App\Form\ExpenseReportLineType;
use App\Form\ExpenseReportRouteLineType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ExpenseReportType extends AbstractType
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
            ->add('Motif')
            ->add('document', FileType::class, [
            'required' => false,
            'mapped' => false,
            'multiple' => true,
        ])
            ->add('expenseReportLines',
                CollectionType::class,
                [
                    'entry_type' => ExpenseReportLineType::class,
                    "label" => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]
            )

            ->add('expenseReportRouteLines',
                CollectionType::class,
                [
                    'entry_type' => ExpenseReportRouteLineType::class,
                    "label" => false,
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
            'data_class' => ExpenseReport::class,
        ]);
    }
}
