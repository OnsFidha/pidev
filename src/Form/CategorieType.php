<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, array('label'=> 'Nom',
            'attr' => array('class' => 'form-control', 
            'style' => 'margin-bottom:15px',
            'required' => True
            )))
            ->add('description',TextareaType::class, array('label'=> 'Description',
            'attr' => array('class' => 'form-control', 
            'style' => 'margin-bottom:15px',
            
            )))
            ->add('Enregistrer', SubmitType::class, array('label'=> 'Enregistrer', 'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-top:15px')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
