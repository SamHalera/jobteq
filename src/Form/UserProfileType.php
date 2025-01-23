<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => true
            ])
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('thumbnailFile', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image([
                        'mimeTypes' => ['image/jpeg', 'image/jpg', 'image/png'],
                        'mimeTypesMessage' => 'Please upload a valid image (JPEG or PNG).',
                    ])
                ]
            ])
            ->add('company', EntityType::class, [
                'class' => Company::class,
                'choice_label' => 'id',
                'disabled' => true
            ])

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
            'data_class' => User::class,
        ]);
    }
}
