<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


use Symfony\Component\Validator\Constraints\Email;


use Symfony\Component\Validator\Constraints\File;

class ProfileEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Profile picture :',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'formFile',
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '40M',
                        'mimeTypes' => [
                            'image/*',"image/jpeg" , "image/png" , "image/tiff" , "image/svg+xml", "image/gif", "image/webp",
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ])
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Name :',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'nameBasic',
                ],
            ])
            ->add('prename', TextType::class, [
                'label' => 'Prename :',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'nameBasic',
                ],
            ])
             ->add('roles', ChoiceType::class, [
                'label' => 'Interest :',
                    'required' => true, // adjust as needed
                    'attr' => [
                        'class' => 'form-control',
                        'id' => 'roles'],
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
                    'multiple' => true, // This allows selecting multiple roles
                    'expanded' => true, // This displays checkboxes instead of a select dropdown
                    // You can add more options or constraints as needed
                ])
            ->add('phone', TextType::class, [
                'label' => 'Phone :',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'phone',
                    'maxlength' => 8, // Limiter à 8 caractères
                    'minlength' => 8, // Limiter à 8 caractères
                    'pattern' => '^[0-9]*$', // Uniquement des chiffres
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[0-9]*$/',
                        'message' => 'Le numéro de téléphone ne peut contenir que des chiffres.',
                    ]),
                ],
            ])
            ->add('email', TextType::class, [
                'label' => 'Email :',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'email',
                ],
                'constraints' => [
                    new Email([
                        'message' => 'L\'adresse email "{{ value }}" n\'est pas valide.',
                        
                    ]),
                ],
            ])
            ->add('birthday', DateType::class, [
                'label' => 'Birthday',
                'html5' => true,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'html5-date-input'
                ],
                'constraints' => [new NotBlank()]
                ]) 
               ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
