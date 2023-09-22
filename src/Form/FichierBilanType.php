<?php

namespace App\Form;

use App\Entity\FichierBilan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FichierBilanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_fichier_bilan')
            ->add('verif_bilan')
            ->add('id_user')
            ->add('id_info_client')
            ->add('id_fichier_bilan')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FichierBilan::class,
        ]);
    }
}
