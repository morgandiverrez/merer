<?php

namespace App\Form;

use App\Entity\Lieux;
use App\Entity\Profil;
use App\Entity\Seance;
use App\Entity\Evenement;
use App\Entity\Formation;
use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class SeanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            ->add('name')
            
            ->add('datetime', DateTimeType::class,[
             'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            ])

            ->add('nombreplace', NumberType::class)

            ->add('formation', EntityType::class, [
            'class' => Formation::class,
            'required' => false,
            
        ])
            ->add('lieux', EntityType::class, [    
            'class' => Lieux::class,
            'multiple' => true,
            'required' => false,
      
        ])
           
            ->add('parcours', ChoiceType::class,[
                'choices' => $options['parcours'],
                'required' => false,
            ])

            ->add('profil', EntityType::class, [
                'class' => Profil::class,
                'multiple' => true, 
                'required' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('profil')
                        ->innerJoin('profil.user', 'user')
                        ->orderBy('user.email', 'ASC')
                        ->andWhere('user.roles LIKE :val0 OR  user.roles LIKE :val1')
                        ->setParameter('val0', '%ROLE_FORMATEURICE%')
                        ->setParameter('val1', '%ROLE_BF%');
                },
            ])

          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Seance::class,
            'parcours' => false,
        ]);

        $resolver->setAllowedTypes('parcours', 'array');
    }
}
