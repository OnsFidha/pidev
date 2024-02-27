<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('name', null, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('prename', null, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'intérêt:',
                'required' => true,
                'attr' => ['class' => 'form-select'], // Using form-select class for Bootstrap 5 styling
                'choices' => [
                    'Musique' => 'Musique',
                    'Peinture' => 'Peinture',
                    'Danse' => 'Danse',
                    'Sculpture' => 'Sculpture',
                    'Photographie' => 'Photographie',
                    'Cinema' => 'Cinema',
                    'Theatre' => 'Theatre',
                    'Litterature' => 'Litterature',
                    'Arts_plastiques' => 'Arts_plastiques',
                    'Artisanat' => 'Artisanat',
                    'Mode' => 'Mode',
                    'Design' => 'Design',
                    // Add more roles here if needed
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('phone', null, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('isVerified', null, [
                'attr' => ['class' => 'form-check-input'] // Using form-check-input class for Bootstrap 5 styling
            ])
            ->add('birthday', null, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('image', null, [
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

