<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddPreferenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('newPreference',TextType::class,[
                "required" => false,
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
