<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'Prénom'])
            ->add('lastName', TextType::class, ['label' => 'Nom'])
            ->add('adress', TextType::class, ['label' => 'Adresse'])
            ->add('city', TextType::class, ['label' => 'Ville'])
            ->add('postal_code', TextType::class, ['label' => 'Code postal'])
            ->add('phone', TextType::class, ['label' => 'Téléphone'])
            ->add('email', TextType::class, ['label' => 'Email'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
