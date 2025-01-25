<?php

namespace App\Form;

use App\Entity\Candidate;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class CandidateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Title is required'
                    ])
                ]
            ])
            ->add('presentation', TextareaType::class, [
                'attr' => [
                    'rows' => 10
                ]
            ])
            ->add('resumeFile', FileType::class, [
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotNull([
                        'message' => 'Please don\'t forget to upload a resume'
                    ]),
                    new File([
                        'mimeTypes' => [
                            'application/pdf',
                            'message' => 'Only pdf are accpeted'
                        ]
                    ])
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
        ]);
    }
}
