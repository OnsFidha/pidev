<?php

namespace App\Form;

use App\Entity\Collaboration;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CollaborationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('disponibilite')
            ->add('competence')
            ->add('cv', FileType::class, [
                'label' => 'CV',
                'required' => false, 
               
            ]);
       
    
          

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Collaboration::class,
        ]);
    }
}
