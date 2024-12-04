<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('userType', ChoiceType::class, [
                'choices' => [
                'Client' => 'Passager',
                'Driver' => 'Conducteur',
                "Both" => "Les deux"
                ],
                'expanded' => true,
                'multiple' => false,
                'data' => $options['default_choice'],
                "attr" => [
                    "class" => "input-place",
                ]
            ])
            ->add('name', TextType::class, [
                "attr" => [
                    "class" => "input-place",
                    "placeholder"=> "Boulanger"
                ],
                "constraints" => [
                    new Assert\NotBlank(message: "Le champ ne doit pas être vide"),
                    new Assert\Length(min: 2, minMessage: "Le nombre de caractère est trop faible",max:50, maxMessage:"Il y a trop de caractère, il en faut maximum 50"),
                    new Assert\Regex(pattern: "/^[a-zA-Z0-9\s.,'!?():-]+$/", message: "Le text n'est pas conforme")
                ],
            ])
            ->add('surname', TextType::class, [
                "attr" => [
                    "class" => "input-place",
                    "placeholder"=> "Tom"
                ],
                "constraints" => [
                    new Assert\NotBlank(message: "Le champ ne doit pas être vide"),
                    new Assert\Length(min: 2, minMessage: "Le nombre de caractère est trop faible",max:50, maxMessage:"Il y a trop de caractère, il en faut maximum 50"),
                    new Assert\Regex(pattern: "/^[a-zA-Z0-9\s.,'!?():-]+$/", message: "Le text n'est pas conforme")
                ],
            ])
            ->add('email', TextType::class, [
                "attr" => [
                    "class" => "input-place",
                    "placeholder"=> "Tom@gmail.com"
                ],
                "constraints"=>[
                    new Assert\NotBlank(message: "Le champ ne doit pas être vide"),
                    new Assert\Length(min:2, minMessage:"Le nombre de caractère est trop faible", max:50, maxMessage:"Il y a trop de caractère, il en faut maximum 50"),
                    new Assert\Email(message:"Ce champ donc contenir un email")
                ],
            ])
            ->add("phone",TelType::class,[
                "required" => false,
                "attr" => [
                    "class" =>"input-place",
                    "placeholder"=> "06 00 00 00 00"
                ],
                "constraints" => [
                    new Assert\Regex(pattern: "/^0[1-9]( [0-9]{2}){4}$/", message: "Le numéro doit être sous cette forme: 06 00 00 00 00")
                ],
            ])
            ->add('pseudo', TextType::class, [
                "attr" => [
                    "class" => "input-place",
                    "placeholder"=> "Tom123"
                ],
                "constraints" => [
                    new Assert\NotBlank(message: "Le champ ne doit pas être vide"),
                    new Assert\Length(min: 2, minMessage: "Le nombre de caractère est trop faible",max:50, maxMessage:"Il y a trop de caractère, il en faut maximum 50"),
                    new Assert\Regex(pattern: "/^[a-zA-Z0-9\s.,'!?():-]+$/", message: "Le text n'est pas conforme")
                ],
            ])
            ->add('password', PasswordType::class, [
                "required" => false,
                "attr" => [
                    "class" => "input-place",
                    "placeholder"=> "**********",
                    "id" => "passwordInput"
                ],
                "constraints" => [
                    new Assert\Length(max: 50, maxMessage: "Le nombre de caractère doit être inférieur à 50"),
                    new Assert\Regex(pattern: "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", message: "Mot de passe non conforme")
                ]
            ])
            ->add('newPassword', PasswordType::class, [
                "required" => false,
                "attr" => [
                    "class" => "input-place",
                    "placeholder"=> "**********",
                    "id" => "newPasswordInput"
                ],
                "constraints" => [
                    new Assert\Length(max: 50, maxMessage: "Le nombre de caractère doit être inférieur à 50"),
                    new Assert\Regex(pattern: "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", message: "Mot de passe non conforme")    
                ]
            ])
            ->add("submit", SubmitType::class, [
                "label"=>"Enregistrer",
                "attr" => [
                    "class" => "standard-btn"
                ]
            ])
        ;
    }

    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => true,           // Enable CSRF protection
            'csrf_field_name' => '_token',  // Default CSRF field name
            'csrf_token_id' => 'user_profile',  // Unique ID for the token
            'default_choice' => null, // Default to no selection
        ]);
    }
}
