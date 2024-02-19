<?php

namespace App\Form;

use App\Entity\Reponse;
use App\Entity\Reclamation;
use App\Form\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reponse',TextareaType::class)
            // ->add('date_reponse')
            // ->add('relation')
//             ->add('relation', EntityType::class, [
//                   'class' => 'App\Entity\Reclamation', 
//                   'choice_label' => 'id', 
//                   'placeholder' => 'Select an id', 
// 
//             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponse::class,
        ]);
    }
}
