<?php

namespace App\Form;

use App\Entity\FichierBilan;
use App\Entity\InfoClient;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddFichierBilanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_fichier_bilan',FileType::class, [
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
            ->add('verif_bilan', CheckboxType::class, [
                'label' => 'Check',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est obligatoire.',
                    ]),
                ],
            ])
            ->add('id_fichier_bilan',ChoiceType::class,[
                'choices'=>$options['fichiers'],
                //label obligatoire pour afficher le nom
                'choice_label'=>'fichier_bilan',
                'expanded' => true, // Afficher comme une série de boutons radio
                'multiple' => false, // Sélection unique
            ])
            ->add('id_annee');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FichierBilan::class,
            'fichiers'=>[]
        ]);
    }
}
