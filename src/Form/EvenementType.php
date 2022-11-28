<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('imageFile', FileType::class, [
                'required' => false,
                'label' => 'Image',
                'attr' => [
                    'placeholder' => 'Choisissez une image',
                ],
            ])
            ->add('backgroundcolor', ColorType::class, [
                'label' => 'Couleur de fond'
            ])
            ->add('bordercolor', ColorType::class, [
                'label' => 'Couleur de la bordure'
            ])
            ->add('textcolor', ColorType::class, [
                'label' => 'Couleur du texte'
            ])
            ->add('beginAt')
            ->add('endAt')
            ->add('public')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
