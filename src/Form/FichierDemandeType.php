<?php

namespace App\Form;

use App\Entity\FichierDemande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FichierDemandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_fichier_demande')
            ->add('id_user')
            ->add('id_fichier')
            ->add('id_info_client')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FichierDemande::class,
        ]);
    }
}
