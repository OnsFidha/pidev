<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        /*->add('ProfilePicture', FileType::class, [
            'mapped' => false,
            'required' => false,
            'label' => 'Profile picture :',
            'attr' => [
                'class' => 'form-control',
                'id' => 'formFile',
            ],
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/*',"image/jpeg" , "image/png" , "image/tiff" , "image/svg+xml", "image/gif", "image/webp",
                    ],
                    'mimeTypesMessage' => 'Please upload a valid image file',
                ])
            ],
        ])*/
            ->add('name', null, [
                'label' => 'Name',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Your name',
                    'id' => 'nameBasic'
                ],
                'constraints' => [new NotBlank()]
                ])
            ->add('prename', null, [
                'label' => 'Prename',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Your prename',
                    'id' => 'nameBasic',
                ],
                'constraints' => [new NotBlank()]
                ])
                ->add('roles', ChoiceType::class, [
                    'label' => 'intérêt :',
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
                ->add('phone', TextType::class, [
                    'label' => 'Phone :',
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control',
                        'id' => 'phone',
                        // 'maxlength' => 8, // Limiter à 8 caractères
                        // 'minlength' => 8, // Limiter à 8 caractères

                        // 'pattern' => '^[0-9]*$', // Uniquement des chiffres
                    ],
                    // 'constraints' => [
                    //     new Regex([
                    //         'pattern' => '/^[0-9]*$/',
                    //         'message' => 'Le numéro de téléphone ne peut contenir que des chiffres.',
                    //     ]),
                    // ],
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
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter your password',
                    'id' => 'password'
                ],
                'constraints' => [new NotBlank()]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
