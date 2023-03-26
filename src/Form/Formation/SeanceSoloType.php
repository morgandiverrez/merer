<?php

namespace App\Form\Formation;

use App\Entity\Formation\Lieux;
use App\Entity\Formation\Profil;
use App\Entity\Formation\Seance;
use App\Entity\Formation\Evenement;
use App\Entity\Formation\Formation;
use Doctrine\ORM\EntityRepository;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class SeanceSoloType extends AbstractType
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

            ->add('profil', EntityType::class, [
                'class' => Profil::class,
                 'multiple' => true,
                 'required' => false,
                'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('profil')
                    ->innerJoin('profil.user', 'u')
                    ->orderBy('u.email', 'ASC')
                    ->andWhere('u.roles LIKE :val0 OR  u.roles LIKE :val1')
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
           
        ]);

      
    }
}
