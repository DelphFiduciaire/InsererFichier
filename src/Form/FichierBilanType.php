<?php

namespace App\Form;

use App\Entity\FichierBilan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\NotBlank;

class FichierBilanType extends AbstractType
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

//            ->add('verif_bilan')
//        ->add('id_user')
        ->add('id_info_client')
        ->add('id_fichier_bilan')
        ->add('id_annee');

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FichierBilan::class,
        ]);
    }
}
