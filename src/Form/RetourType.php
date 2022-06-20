<?php

namespace App\Form;

use App\Entity\Retour;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RetourType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note_contenu')
            ->add('remarque_contenu', TextareaType::class)
            ->add('note_animation')
            ->add('remarque_animation')
            ->add('note_implication')
            ->add('remarque_implication')
            ->add('note_reponse_atente')
            ->add('remarque_reponse_attente')
            ->add('note_niv_competence')
            ->add('remarque_niv_competence')
            ->add('note_utilite')
            ->add('remarque_utilite')
            ->add('note_generale')
            ->add('remarque_generale')
            ->add('apport_generale')
            ->add('plus_aimer')
            ->add('moins_aimer')
            ->add('aimer_voir')
            ->add('mot_fin')
            ->add('seance')
            ->add('profil')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Retour::class,
        ]);
    }
}
