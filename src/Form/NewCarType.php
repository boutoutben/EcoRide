<?php

namespace App\Form;

use App\Entity\Mark;
use Faker\Provider\ar_EG\Text;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class NewCarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("licensePlate", TextType::class,[
                "required" => false,
                "constraints" =>[
                    new Assert\NotBlank(message:"Le champ ne peut pas être vide"),
                    new Assert\Regex("/^[A-Z]{2}-\d{3}-[A-Z]{2}$/", "La plaque d'immatriculation n'est pas conforme")
                ],
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "xxx-111-xx"
                ]
            ])
            ->add("firstImmatriculation", DateType::class, [
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "06/05/2021"
                ]
            ])
            ->add("mark", EntityType::class, [
                "class" => Mark::class,
                "attr" => [
                    "class" => "input-place",
                ]
            ])
            ->add("model", TextType::class, [
                "constraints" => [
                    new Assert\NotBlank(message: "Le champ ne peut pas être vide"),
                    new Assert\Regex("/^[A-Za-z0-9&'’.\- ]{2,50}$/", "Le modèle n'est pas conforme et le nombre de caractère doit être compris entre 2 et 50")
                ],
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "205"
                ]
            ])
            ->add("color", TextType::class, [
                "constraints" => [
                    new Assert\NotBlank(message: "Le champ ne peut pas être vide"),
                    new Assert\Regex("/^[A-Za-z0-9&'’.\- ]{2,50}$/", "La couleur n'est pas conforme et le nombre de caractère doit être compris entre 2 et 50")
                ],
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "vert"
                ]
            ])
            ->add("energie", ChoiceType::class, [
            'choices' => [
                'thermique' => 'thermal',
                'hybride' => 'hybrid',
                "Electrique" => "Electrique"
            ],
                "attr" => [
                    "class" => "input-place",
                ]
            ])
            ->add("nbPassenger", IntegerType::class, [
                "constraints" => [
                    new Assert\NotBlank(message: "Le champ ne peut pas être vide"),
                    new Assert\Positive(message: "Le nombre de passager doit être supérieur à 0")
                ],
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "4"
                ]
            ])
            ->add("submit", SubmitType::class, [
                "label" => "Enregistrer",
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
            'csrf_token_id' => 'new_car',  // Unique ID for the token
            'default_choice' => null, // Default to no selection
        ]);
    }
}
