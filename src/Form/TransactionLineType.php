<?php

namespace App\Form;

use App\Entity\TransactionLine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TransactionLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
             ->add('label', TextType::class, ['required' => false])
            ->add('date')
            ->add('amount')
            ->add('urlProof')
            ->add('quote')
            ->add('chartOfAccounts')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TransactionLine::class,
        ]);
    }
}
