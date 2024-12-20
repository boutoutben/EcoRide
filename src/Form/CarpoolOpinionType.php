<?php

namespace App\Form;

use Faker\Provider\ar_EG\Text;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CarpoolOpinionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('satisfied', ChoiceType::class, [
                'choices' => [
                    'Bien passé' => true,
                    'Mal passé' => false,
                ],
                'expanded' => true,
                'multiple' => false,
                'data' => $options['default_choice'],
                "attr" => [
                    "class" => "input-place",
                ]
            ])
            ->add("mark", ChoiceType::class, [
                "choices" => [
                    "1" => 1,
                    "2" => 2,
                    "3" => 3,
                    "4" => 4,
                    "5" => 5
                ],
                "expanded" => true,
                "multiple" => false,
                "data" => 5,
                "attr" => [
                    "class" => "starColor"
                ]
            ])
            ->add("opinion", TextareaType::class,[
                "constraints" => [
                    new Assert\Regex([
                        'pattern' => "/^[\wÀ-ÖØ-öø-ÿ.,!'?() -]{10,500}$/",
                        'message' => 'Votre opinion doit comporter entre 10 et 500 caractères valides.',
                    ]),
                ],
                "required" => false,
                "attr" => [
                    "class" => "input-place"
                ]
            ])
            ->add("submit", SubmitType::class, [
                "attr" => [
                    "class" => "standard-btn"
                ],
                "label" => "Valider"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "default_choice" => 'great'
        ]);
    }
}
