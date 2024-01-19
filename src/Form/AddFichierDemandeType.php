<?php

namespace App\Form;

use App\Entity\Fichier;
use App\Entity\FichierDemande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

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
            ->add('verif')
            ->add('id_fichier',ChoiceType::class,[
                'choices'=>$options['fichiers'],
                //label obligatoire pour afficher le nom
                'choice_label'=>'nom_fichier'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FichierDemande::class,
            'fichiers' => []
        ]);
    }
}
