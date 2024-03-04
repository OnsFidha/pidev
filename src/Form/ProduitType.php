<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Categorie;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, array('label'=> 'Nom',
             'attr' => array('class' => 'form-control', 
             'style' => 'margin-bottom:15px',
              
             )))

            ->add('prix',NumberType::class, array('label'=> 'Prix', 'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px',
              'error_bubbling' => true,
            )))

            ->add('quantite',NumberType::class, array('label'=> 'Quantité', 
            'attr' => array('class' => 'form-control', 
            'style' => 'margin-bottom:15px', 
            'error_bubbling' => true,
            )))

            ->add('description',TextareaType ::class, array('label'=> 'Description', 
            'attr' => array('class' => 'form-control', 
            'style' => 'margin-bottom:15px'
            ,'required' => 'Le champ message est obligatoire.')))

           ->add('image',FileType::class, [
            'data_class'=>null,
                'label' => 'Image (image)',
                'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px'),
                // unmapped means that this field is not associated to any entity property
              //'mapped' => true,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using attributes
                // in the associated entity, so you can use the PHP constraint classes
               'constraints' => [
                    new Image([
                       'maxSize' => '1024k',
                      
                      
                    ])
                ],
            ])
            ->add('categorie',EntityType::class, [
                // looks for choices from this entity
                'class' => Categorie::class,
                'attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px'),
                // uses the User.username property as the visible option string
                'choice_label' => 'nom',
                
            
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('Enregistrer', SubmitType::class, array('label'=> 'Enregistrer', 'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-top:15px')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
