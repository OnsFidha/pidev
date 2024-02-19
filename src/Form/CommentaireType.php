<?php

namespace App\Form;

use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('text',
            TextareaType::class, [
            'required' => true,
            'label' => 'Le texte de commentaire',
            'constraints' => [
                new NotBlank(['message' => 'Veuillez saisir le texte de votre commentaire.']),
                new Length([
                    'min' => 5,
                    'max' => 255,
                    'minMessage' => 'Le commentaire doit contenir au moins {{ limit }} caractères.',
                    'maxMessage' => 'Le commentaire ne peut pas dépasser {{ limit }} caractères.'
                ])
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
