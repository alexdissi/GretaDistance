<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email')
            ->add('login')
            ->add('password', PasswordType::class, [
                'empty_data' => '',
                'hash_property_path' => 'password',
                'mapped' => false,
                'required' => false,
            ])
            ->add('civilite', ChoiceType::class, [
                'placeholder' => '-- Sélectionnez une civilité --',
                'choices' => [
                    'Homme' => 'Homme',
                    'Femme' => 'Femme',
                    'Non binaires' => 'Non binaires',
                ],
            ])
            ->add('age')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Enseignant' => 'ROLE_ENSEIGNANT',
                    'Admin' => 'ROLE_ADMIN',
                    // Add more role options as needed
                ],
                'multiple' => true,
                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
