<?php

namespace App\Form;

use App\Entity\TransactionLine;
use Doctrine\DBAL\Types\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class TransactionLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
             ->add('label', TextType::class, ['required' => false])
            ->add('date', DateType::class,['widget' => 'single_text',])
            ->add('amount', MoneyType::class)
            ->add('urlProof')
            ->add('quote', TextAreaType::class)
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
