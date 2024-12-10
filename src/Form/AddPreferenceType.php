<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AddPreferenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('newPreference',TextType::class,[
                "required" => false,
            "constraints" => [
                new Assert\NotBlank(message: "Le champ ne peut pas être vide"),
                new Assert\Regex("/^[A-Z]{2}-\d{3}-[A-Z]{2}$/", "La préférence n'est pas conforme")
            ],
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "préference",
                ]
            ])
            ->add("submit",SubmitType::class,[
                "label" => "Ajouter",
                "attr" => [
                    "class" => "standard-btn"
                ]
            ])
        ;
    }

    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
