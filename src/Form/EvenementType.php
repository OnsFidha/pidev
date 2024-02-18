<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,['required'=>true,'label'=>'Nom'])
            ->add('date_debut', DateType::class, [
                'required' => true,
                'label' => 'Date début',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('date_fin', DateType::class, [
                'required' => true,
                'label' => 'Date fin',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('description',TextareaType::class, [
                'required' => true,
                'label' => 'Description', 
                'attr' => ['class' => 'form-control', 'rows' => 4], 
            ])
            ->add('lieu',TextType::class,['required'=>true,'label'=>'Lieu','attr' => ['class' => 'form-control'],])
           
            ->add('nbre_max',NumberType::class,['required'=>true,'label'=>'Nombre de participants maximale','attr' => ['class' => 'form-control'],])
            ->add('image', FileType::class, [
                'label' => 'Image)',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image',
                    ])
                ],
            ])
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
