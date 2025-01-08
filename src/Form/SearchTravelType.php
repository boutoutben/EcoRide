<?php

namespace App\Form;

use App\Entity\Carpool;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeImmutableToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class SearchTravelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("startPlace", SearchType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le champ ne peut pas être vide']),
                    new Assert\Length(max: 50, min: 2)
                ],
                "data" => $options["default_start_place"],
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "Lieu de départ"
                ]
                ])
            ->add("endPlace", SearchType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le champ ne peut pas être vide']),
                    new Assert\Length(max:50,min:2)
                ],
                "data" => $options["default_end_place"],
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "Lieu d'arrivée"
                ]
            ])
            ->add('startDate', DateTimeType::class, [
                'constraints'=> [
                    new Assert\NotBlank(["message"=>"Le champ ne peut pas être vide"]),
                ],
                'required' => true,
                "data"=> new DateTime(),
                'widget' => 'single_text',
                'html5' => true,
                "attr" => [
                    "class" => "input-place",
                ]
            ])
            ->add("submit", SubmitType::class, [
                'label' => 'Rechercher',
                "attr" => [
                    "class" => "standard-btn"
                ],
            ]);
    }
    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "default_start_place" => null,
            "default_end_place" => null
        ]);
    }
}
