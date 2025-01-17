<?php

namespace App\Form;

use App\Entity\License;
use App\Entity\LicenseConfig;
use App\Entity\StatusEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class LicenseType extends AbstractType
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
            ->add('credits', NumberType::class, [
                'constraints' => [
                    new Positive([
                        'message' => 'Only positive numbers'
                    ])
                ]
            ])
            ->add('price', NumberType::class, [
                'label' => 'License\'s price in €',
                'constraints' => [
                    new Positive([
                        'message' => 'Only positive numbers'
                    ])
                ]
            ])
            ->add('status', EnumType::class, [
                'class' => StatusEnum::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => License::class,
        ]);
    }
}
