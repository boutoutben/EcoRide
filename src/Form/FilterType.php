<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isEcologique', ChoiceType::class, [
                "required" => false,
                'expanded' => true,
                'multiple' => true,
                'choices' => [
                    'Ecologieque' => 'Ecologique',
                ],
            ])
            ->add("maxPrice", IntegerType::class, [
                "constraints" => [
                    new Assert\PositiveOrZero(message:"Le champ n'est peut pas être inférieur à zéro")
                ],
                "required" => false,
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "6",
                ],
                
            ])
            ->add("maxTime",TimeType::class,[
                "required" => false,
                "attr" => [
                    "class" => "input-place",
                ],
                
            ])
            ->add("minMark",IntegerType::class,[
                "constraints" => [
                    new Assert\PositiveOrZero(message:"Le champ n'est peut pas être inférieur à zéro"),
                    new Assert\LessThan(5,message:"Le champ ne peut pas être supérieur à 5")
                ],
                "required" => false,
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "5",
                ],
            ])
            ->add("submit", SubmitType::class, [
                "label" => "Filtrer",
                "attr" => [
                    "class" => "standard-btn"
                ]
            ])
        ;
    }
}
