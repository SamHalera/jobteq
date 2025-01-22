<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\License;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Name is required'
                    ])
                ]
            ])
            ->add('logoFile', FileType::class, [
                'required' => false
            ])
            ->add('imageFile', FileType::class, [
                'required' => false
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'attr' => [
                    'rows' => 10
                ]
            ])
            // ->add('_token', HiddenType::class, [
            //     'mapped' => false, // CSRF tokens should not be mapped to the entity
            //     'csrf_protection' => true,
            //     'csrf_field_name' => '_csrf_token',
            //     'csrf_token_id' => 'company_form', // A unique identifier for this form
            // ])
            ->add('Update', SubmitType::class, [
                'attr' => [
                    'class' => 'my-3 btn btn-primary'
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
