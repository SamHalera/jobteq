<?php

namespace App\Form;

use App\Entity\LicenseConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class LicenseConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Name is required!'
                    ])
                ]
            ])
            ->add('licenses', CollectionType::class, [
                'entry_type' => LicenseType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                // 'prototype_name' => '__name2__',
                'attr' => [
                    // 'data-controller' => 'collection',
                    'class' => "d-flex flex-wrap gap-4",
                    // 'data-prototype-name' => '__name2__',
                    // 'data-entry-add-label' => 'Add Licese',
                    // 'data-entry-remove-label' => 'Remove Licese',

                ]
            ])
            ->add('_token', HiddenType::class, [
                'mapped' => false, // CSRF tokens should not be mapped to the entity

                'csrf_protection' => true,
                'csrf_field_name' => '_csrf_token',
                'csrf_token_id' => 'license_config_form', // A unique identifier for this form
            ])
            // ->add('validate', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LicenseConfig::class,
        ]);
    }
}
