<?php

namespace App\Form;

use App\Entity\Fichier;
use App\Entity\FichierDemande;
use App\Entity\InfoClient;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;


class FichierDemandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_fichier_demande', FileType::class, [
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])

            ->add('verif', CheckboxType::class, [
                'label' => 'Check',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ est obligatoire.',
                    ]),
                ],
            ])
            ->add('id_info_client' , EntityType::class, [
                'class' => InfoClient::class,
                //j'appelle une requete sql dans le form pour le filtrer sans l'id 2 qui est le par défaut
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ic')
                        ->where('ic.mail_pro != :mailpro')
                        ->setParameter('mailpro','pardefaut@email.com');
                },
            ])
            ->add('id_fichier' , EntityType::class, [
                'class' => Fichier::class,
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FichierDemande::class,
        ]);
    }
}
