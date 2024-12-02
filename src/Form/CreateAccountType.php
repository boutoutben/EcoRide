<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CreateAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',TextType::class,[
                "constraints"=>[
                    new Assert\NotBlank(message: "Le champ ne doit pas être vide"),
                    new Assert\Length(min:2, minMessage:"Le nombre de caractère est trop faible", max:50, maxMessage:"Il y a trop de caractère, il en faut maximum 50"),
                    new Assert\Email(message:"Ce champ donc contenir un email")
                ],
                "attr" => [
                    "class"=> "input-place",
                    "placeholder"=>"Email"
                ]
            ])
            ->add('pseudo', TextType::class, [
                "constraints" => [
                    new Assert\NotBlank(message: "Le champ ne doit pas être vide"),
                    new Assert\Length(min: 2, minMessage: "Le nombre de caractère est trop faible",max:50, maxMessage:"Il y a trop de caractère, il en faut maximum 50"),
                    new Assert\Regex(pattern: "/^[a-zA-Z0-9\s.,'!?():-]+$/", message: "Le pseudo n'est pas conforme")
                ],
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "Pseudo"
                ]
            ])
            ->add('password', PasswordType::class, [
                "label"=>"password",
                "constraints" => [
                    new Assert\NotBlank(message: "Le champ ne doit pas être vide"),
                    new Assert\Length(max:50, maxMessage:"Il y a trop de caractère, il en faut maximum 50"),
                    new Assert\Regex(pattern:'/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', message:"Le mot de passe n'est pas assez fort")
                ],
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "Mot de passe sécurisé"
                ]
            ])
            ->add('passwordEgain', PasswordType::class, [
                "constraints" => [
                    new Assert\NotBlank(message: "Le champ ne doit pas être vide"),
                    new Assert\Length(min: 2, minMessage: "Le nombre de caractère est trop faible", max: 50, maxMessage: "Il y a trop de caractère, il en faut maximum 50"),
                    new Assert\Regex(pattern: "/^[a-zA-Z0-9\s.,'!?():-]+$/", message: "La confirmation du mot de passe n'est pas conforme"),
                ],
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "Confirmation du mot de passe",
                ]
            ])
            ->add('submit', SubmitType::class,[
                "label"=>"Créer un compte",
                "attr" => [
                    "class" => "standard-btn"
                ]
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true, // Enable CSRF protection
            'csrf_field_name' => '_token', // Name of the hidden field
            'csrf_token_id' => 'create_account', // Unique token ID
        ]);
    }
}
