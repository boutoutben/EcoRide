<?php

namespace App\Form;

use App\Entity\Carpool;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchTravelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("start_place",SearchType::class, [
            'required' => true,
            "attr" => [
                "class" => "input-place",
                "placeholder" => "Lieu de départ"
                ]
            ])
            ->add("end_place", SearchType::class,[
            "attr" => [
                "class" => "input-place",
                "placeholder" => "Lieu d'arrivée"
            ]
            ])
            ->add("date", DateTimeType::class, [
            "attr" => [
                "class" => "input-place",
            ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
