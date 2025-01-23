<?php

namespace App\Form;

use App\Entity\User;
use App\Service\SessionManagerService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{

    public function __construct(private readonly SessionManagerService $sessionManagerService) {}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('thumbnail', TextType::class, [
                'required' => false,
                'label' => 'Your profile picture (TO DO)'
            ])
            ->add('email', EmailType::class)
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])

            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('_token', HiddenType::class, [
                'mapped' => false, // CSRF tokens should not be mapped to the entity

                'csrf_protection' => true,
                'csrf_field_name' => '_csrf_token',
                'csrf_token_id' => 'registration_form', // A unique identifier for this form
            ])
        ;
        if ($options['add_roles_field']) {
            $builder->add('youAre', ChoiceType::class, [
                'mapped' => false,
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'candidate' => 'candidate',
                    'company' => 'company'
                ],
                'constraints' => [
                    new Choice([
                        'choices' => ["candidate", "company"],
                        'message' => 'You should choose one role.',
                    ]),
                ],
            ]);
        }
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $addRolesField = true;

        if ($this->sessionManagerService->getInvitationIsAccepted('invitationIsAccepted')) {

            $addRolesField = !$this->sessionManagerService->getInvitationIsAccepted('invitationIsAccepted');
        }
        $resolver->setDefaults([
            'data_class' => User::class,
            'add_roles_field' => $addRolesField
        ]);
    }
}
