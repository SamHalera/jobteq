<?php

namespace App\Form;

use App\Entity\SuperAdminJobConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class JobOffersConfigurationType extends AbstractType
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
            ->add('categories', CollectionType::class, [
                'entry_type' => CategoryType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'attr' => [
                    // 'data-controller' => 'collection',
                    'class' => "d-flex flex-wrap gap-4",
                    'data-prototype-name' => '__name2__',
                    'data-entry-add-label' => 'Add Category',
                    'data-entry-remove-label' => 'Remove Category',

                ]
            ])
            ->add('tags', CollectionType::class, [
                'entry_type' => TagType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'attr' => [
                    // 'data-controller' => 'collection',
                    'class' => "d-flex flex-wrap gap-4",
                    'data-prototype-name' => '__name2__',
                    'data-entry-add-label' => 'Add Tag',
                    'data-entry-remove-label' => 'Remove Tag',

                ]
            ])
            ->add("validate", SubmitType::class, [
                // 'attr' => [
                //     'data-action' => "modal-form#submitForm"
                // ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SuperAdminJobConfig::class,
        ]);
    }
}
