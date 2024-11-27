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
use Symfony\Component\Validator\Constraints\NotBlank;

class SearchTravelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("startPlace", SearchType::class, [
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "Lieu de départ"
                ]
                ])
            ->add("endPlace", SearchType::class, [
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "Lieu d'arrivée"
                ]
            ])
            ->add('startDate', DateTimeType::class, [
                'required' => true,
                'html5' => true,
                "attr" => [
                    "class" => "input-place"
                ]
            ])
            ->add("submit", SubmitType::class, [
                'label' => 'Rechercher',
                "attr" => [
                    "class" => "standard-btn"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carpool::class,
            'start_date' => null,
            'end_date' => null,
        ]);
    }
}
