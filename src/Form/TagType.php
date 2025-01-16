<?php

namespace App\Form;

use App\Entity\JobOffer;
use App\Entity\StatusEnum;
use App\Entity\SuperAdminJobConfig;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Name",
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom de la sous-tâche est obligatoire'
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
            'data_class' => Tag::class,
        ]);
    }
}
