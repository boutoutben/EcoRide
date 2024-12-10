<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPreferencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $preferences = ($options['data_preference'])->getPreference(); // Retrieve the array of preferences

        $choices = array_column($preferences, 0);
        $builder
            ->add('choicePreferences', ChoiceType::class, [
                "choices" => array_combine($choices, $choices),
                'expanded' => true,
                'multiple' => true,
                'data' => $options['selectedPreferences'],
            ])
            ->add("submit", SubmitType::class,[
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
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "data_preference" => null,
            'selectedPreferences' => [],
        ]);
    }
}
