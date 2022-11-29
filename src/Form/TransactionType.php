<?php

namespace App\Form;

use App\Entity\BP;
use App\Entity\Event;
use App\Entity\Exercice;
use App\Entity\Transaction;
use App\Form\TransactionLineType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           ->add('exercice', EntityType::class,[
                'class' => Exercice::class,
             'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                ->orderBy('u.date', 'DESC');
            },
            ])
            ->add('closure', ChoiceType::class, [
                'choices'  => [
                    'Non' => false,
                    'Oui' => true,
                   
                ],
            ])
            ->add('quote', TextAreaType::class)
            ->add('transactionLines', CollectionType::class,[
                    'entry_type' => TransactionLineType::class,
                    "label" => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]
            )
            ->add('event',  EntityType::class, [
            'class' => Event::class,
            ])
            ->add('bp',  EntityType::class, [
            'class' => BP::class,
            ])
            ->add('financementLine',  EntityType::class, [
            'class' => FinancementLine::class,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
