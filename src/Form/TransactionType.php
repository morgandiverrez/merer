<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Transaction;
use App\Entity\Exercice;
use App\Entity\BP;
use App\Form\TransactionLineType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           ->add('exercice', EntityType::class,[
                'class' => Exercice::class,
            ])
            ->add('closure', ChoiceType::class, [
                'choices'  => [
                    'Oui' => true,
                    'Non' => false,
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
