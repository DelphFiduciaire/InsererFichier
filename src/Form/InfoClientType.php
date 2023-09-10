<?php

namespace App\Form;

use App\Entity\InfoClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InfoClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('mail_pro')
            ->add('nom_societe')
            ->add('adresse')
            ->add('num_pro')
            ->add('num')
            ->add('cp')
            ->add('ville')
            ->add('siret')
            ->add('id_user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InfoClient::class,
        ]);
    }
}
