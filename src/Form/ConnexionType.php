<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ConnexionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class,[
                'attr' => [
                    "class" => "input-place",
                    "placeholder" =>"Pseudo",
                ],
            ])
            ->add("password", PasswordType::class, [
                "attr" => [
                    "class" => "input-place",
                    "placeholder" =>"mot de passe",
                ]
            ])
            ->add("submit", SubmitType::class, [
                "attr" => [
                    "class" => "standard-btn"
                ],
                "label" => "Connexion"
            ])
        ;
    }

    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true, // Enable CSRF protection
            'csrf_field_name' => '_token', // Name of the hidden field
            'csrf_token_id' => 'create_account', // Unique token ID
        ]);
    }

}
