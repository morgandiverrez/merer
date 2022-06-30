<?php

namespace App\Form;

use App\Entity\Association;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AssociationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code')
            ->add('image', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [new Image(['maxSize' => '1024k'])]
                ])
            ->add('sigle')
            ->add('name')
            ->add('categorie')
            ->add('description', TextareaType::class)
            ->add('fede_filliere')
            ->add('fede_territoire')
            ->add('local')
            ->add('adresse_mail', EmailType::class)
            ->add('date_election', BirthdayType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Association::class,
        ]);
    }
}
