<?php

namespace App\Form;

use App\Entity\Car;
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

class EditCarType extends AbstractType
{
    private string $defaultName = "edit_form";
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->defaultName = $options['default_name'];

        $builder
            ->add("licensePlate", TextType::class, [
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "xxx-111-xx"
                ]
            ])
            ->add("firstRegistration", DateType::class, [
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "06/05/2021"
                ],
                "data" => $options['default_date']
            ])
            ->add("mark", EntityType::class, [
                "class" => Mark::class,
                'data' => $options['default_choice'],
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "Citroën"
                ]
            ])
            ->add("model", TextType::class, [
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "205"
                ]
            ])
            ->add("color", TextType::class, [
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
                "attr" => [
                    "class" => "input-place",
                    "placeholder" => "4"
                ]
            ])
            ->add("submit", SubmitType::class, [
                "label" => "Modifier",
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
            'data_class' => Car::class,
            'csrf_protection' => true,           // Enable CSRF protection
            'csrf_field_name' => '_token',  // Default CSRF field name
            'csrf_token_id' => 'new_car',  // Unique ID for the token
            'default_choice' => null, // Default to no selection
            'default_date' => null,
            "default_name" => "edit_car_form"
        ]);
    }

    public function getBlockPrefix(): string
    {
        // Use the captured name from options.
        return $this->defaultName ?? parent::getBlockPrefix();
    }

}
