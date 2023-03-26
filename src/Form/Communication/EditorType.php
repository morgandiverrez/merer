<?php

namespace App\Form\Communication;

use App\Entity\Communication\Editor;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [new Image(['maxSize' => '1024k'])]
            ])
            ->add('name')
            ->add('mail', EmailType::class, ['required' => false,])
            ->add('instagram')
            ->add('facebook')
            ->add('adress')
            ->add('description', TextareaType::class, ['required' => false,])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('user')
                        ->leftJoin('user.editor', 'editor')
                        ->where('  editor.id IS NULL');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Editor::class,
        ]);
    }
}
