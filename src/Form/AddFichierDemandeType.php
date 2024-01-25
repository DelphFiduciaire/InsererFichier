<?php

namespace App\Form;

use App\Entity\Fichier;
use App\Entity\FichierDemande;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddFichierDemandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_fichier_demande',FileType::class, [
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Merci de mettre un fichier PDF',
                    ])
                ],
            ])
            ->add('verif',CheckboxType::class, [
        'label' => 'verification',
        'required' => true,
        'constraints' => [
            new NotBlank([
                'message' => 'Ce champ est obligatoire.',
            ]),
        ],
    ])
            ->add('id_fichier', ChoiceType::class, [
                'choices' => $options['fichiers'],
                'choice_label' => 'nom_fichier',
                'attr' => ['class' => 'form-control mt-2'],
                'expanded' => true, // Afficher comme une série de boutons radio
                'multiple' => false, // Sélection unique
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FichierDemande::class,
            'fichiers' => []
        ]);
    }
}
