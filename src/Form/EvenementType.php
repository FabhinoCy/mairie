<?php

namespace App\Form;

use App\Entity\Evenement;

use Doctrine\DBAL\Types\DateTimeImmutableType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
            ->add('beginAt', DateTimeType::class, [
                'label' => 'Date de début de l\'événement',
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('endAt', DateTimeType::class, [
                'label' => 'Date de fin de l\'événement',
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('public', CheckboxType::class, [
                'label' => 'Événement public',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
