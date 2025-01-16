<?php

namespace App\Form;

use App\Entity\RoleConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class RoleConfigType extends AbstractType
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
            ->add('roles', CollectionType::class, [
                'entry_type' => RoleType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'prototype_name' => '__name2__',
                'attr' => [
                    // 'data-controller' => 'collection',
                    'class' => "d-flex flex-wrap gap-4",
                    'data-prototype-name' => '__name2__',
                    'data-entry-add-label' => 'Add Role',
                    'data-entry-remove-label' => 'Remove Role',

                ]
            ])
            ->add('validate', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RoleConfig::class,
        ]);
    }
}
