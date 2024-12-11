<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Mark;
use App\Repository\CarRepository;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CreateCarpoolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $counter = 0;
        $choices = [];
        foreach ($options['entities'] as $entity) {
            $counter++;
            $choices['Voiture ' . $counter] = $entity->getId(); // Label => Value
        }
        $choices["autre"] = "other";
        $counter=0;
        $builder
            ->add('startPlace',SearchType::class,[
                "constraints" => [
                    new Assert\NotBlank(message: "Le champ ne peut pas être vide"),
                    new Assert\Regex("/^[A-Za-z0-9&'’.\- ]{2,50}$/", "Le lieu de départ n'est pas conforme et le nombre de caractère doit être compris entre 2 et 50")
                ],
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "Lieu de départ"
                ]
            ])
            ->add('endPlace', SearchType::class, [
                "constraints" => [
                    new Assert\NotBlank(message: "Le champ ne peut pas être vide"),
                    new Assert\Regex("/^[A-Za-z0-9&'’.\- ]{2,50}$/", "Le lieu d'arriver n'est pas conforme et le nombre de caractère doit être compris entre 2 et 50")
                ],
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "Lieu d'arrivé"
                ]
            ])
            ->add('startDate', DateTimeType::class, [
                'widget' => 'single_text',
                "attr" => [
                    "class" => "input-place",
                ]
            ])
            ->add('endDate', DateTimeType::class, [
                'widget' => 'single_text',
                "attr" => [
                    "class" => "input-place",
                ]
            ])
            ->add('carChoice', ChoiceType::class, [
                'choices' => $choices,
                "attr" => [
                    "class" => "input-place"
                ]
            ])
            ->add("credit", IntegerType::class, [
            "constraints" => [
                new Assert\NotBlank(message: "Le champ ne peut pas être vide"),
                new Assert\GreaterThanOrEqual(value:2, message:"Le crédit doit être supérieur ou égal à 2")
            ],
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "prix en crédits"
                ]
            ])
            ->add("licensePlate", TextType::class, [
                "required" => false,
                "constraints" => [
                    new Assert\Regex("/^[A-Z]{2}-\d{3}-[A-Z]{2}$/", "La plaque d'immatriculation n'est pas conforme")
                ],
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "xxx-111-xx"
                ]
            ])
            ->add("firstImmatriculation", DateType::class, [
                "required" => false,
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
                "required" => false,
                "constraints" => [
                    new Assert\Regex("/^[A-Za-z0-9&'’.\- ]{2,50}$/", "Le modèle n'est pas conforme et le nombre de caractère doit être compris entre 2 et 50")
                ],
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "205"
                ]
            ])
            ->add("color", TextType::class, [
                "required" => false,
                "constraints" => [
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
                "required" => false,
                "constraints" => [
                    new Assert\Positive(message: "Le nombre de passager doit être supérieur à 0"),
                    new Assert\LessThan(value:10, message:"Le nombre de passager doit être inférieur à 10")
                ],
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "4"
                ]
            ])
            ->add("submit", SubmitType::class, [
                "label" => "Enregistrer",
                "attr" => [
                    "class" => "standard-btn",
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "entities" => []
        ]);
    }
}
