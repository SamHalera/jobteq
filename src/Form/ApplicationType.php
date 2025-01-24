<?php

namespace App\Form;

use App\Entity\Application;
use App\Entity\JobOffer;
use App\Entity\User;
use App\Repository\CandidateRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class ApplicationType extends AbstractType
{

    private CandidateRepository $candidateRepo;
    public function __construct(CandidateRepository $candidateRepo)
    {
        $this->candidateRepo = $candidateRepo;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('resumeFile', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => ['application/pdf'],
                        'mimeTypesMessage' => 'Please upload a valid image PDF.',
                    ])
                ]

            ])
            ->add('customPresentation', TextareaType::class, [
                'data' => $this->candidateRepo->findPresentation(),
                'attr' => [
                    'rows' => 10
                ]
            ])
            ->add('motivationalMessage', TextareaType::class, [
                'attr' => [
                    'rows' => 10
                ]
            ])
            ->add('Apply', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Application::class,
        ]);
    }
}
