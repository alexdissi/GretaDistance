<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('contenu', CKEditorType::class)
            ->add('dateDebut')
            ->add('dateFin')
            ->add('video', FileType::class, [
                'label' => 'video',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'form-control-file'],
            ])
            ->add('fichier', FileType::class, [
                'label' => 'fichier',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'form-control-file'],
            ])
            ->add('image', FileType::class, [
                'label' => 'image',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'form-control-file'],
            ])
            ->add('alt')
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choices' => $options['categorie'], // Use the passed options to populate the choices
                'choice_label' => 'nom', // Replace 'name' with the actual property of the Categorie entity to display
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
            'categorie' => null,
        ]);
    }
}
