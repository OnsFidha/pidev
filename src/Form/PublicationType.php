<?php

namespace App\Form;

use App\Entity\Publication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;



class PublicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('type', ChoiceType::class, [
            'choices' => [
                'choisir '=>null,
                'offre' => 'offre',
                'ordinaire' => 'ordinaire',
            ],
            'required' => true, 
        ])
            ->add('text', TextareaType::class, [
                'required' => true,
                'label' => 'Description', 
                'attr' => ['class' => 'form-control', 'rows' => 4], 
            ])
            ->add('lieu', CountryType::class, [
                'label' => 'Pays', 
                'required' => true, 
            ])
            ->add('photo');
            
            
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Publication::class,
        ]);
    }
}
