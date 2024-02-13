<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Publication non visible sur la plateforme ' => 'type1',
                    'Difficulté à publier du contenu ' => 'type2',
                    'Difficulté à trouver des collaborations appropriées' => 'type3',
                    'Problèmes de communication avec les collaborateurs' => 'type4',
                    'Difficulté à utiliser certaines fonctionnalités de l \'application' => 'type5',
                    'Violation des conditions d\'utilisation de la plateforme' => 'type6',
                    'Besoin d\'assistance pour résoudre des problèmes liés au compte' => 'type7',
                    
                ],
                'placeholder' => 'Choisir le type de réclamation', 
                'required' => true, 
            ])
            ->add('description')
            // ->add('etat')
            //->add('date_creation')
           ->add('save',SubmitType::class )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
