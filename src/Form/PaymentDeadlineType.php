<?php

namespace App\Form;

use App\Entity\PaymentDeadline;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentDeadlineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('extpectedAmount', MoneyType::class,[
                'label'=>"Montant", 
                'attr' => array('class'=>'my-3')
            ])
            ->add('expectedPaymentDate', DateType::class,[
                'label'=>"Date de paiement", 
                'attr' => array('class'=>'my-3'),
                'years' => range(date('Y'),date('Y')+1),
                'days' => range(5,30,5)
                ])
            ->add('expectedMeans', ChoiceType::class, [
                'label' => "Moyen de paiement", 'attr' => array('class' => 'my-3'),
                'choices'  => [
                    "Chéque" => "Chéque",
                    "Virement Bancaire" => "Virement Bancaire",
                    "Paiement en Ligne via HelloAsso" => "HelloAsso",
                    "Paiement en Ligne via LyfPay" => "LyfPay",
                    
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PaymentDeadline::class,
        ]);
    }
}
