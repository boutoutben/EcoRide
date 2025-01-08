<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use MongoDB\Client;

class UserPreferencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $client = new Client("mongodb://localhost:27017");

        // Accéder à la collection "preferences" dans la base "ecoride"
        $collection = $client->ecoride->preferences;

        // Récupérer toutes les préférences de l'utilisateur
        $cursor = $collection->find(['user' => $options["usernamePreference"]]);

        // Construire les choix pour le formulaire
        $choices = [];
        foreach ($cursor as $document) {
            $label = $document['preference'] ?? 'Préférence inconnue'; // Libellé lisible pour l'utilisateur
            $value = (string)($document['_id'] ?? ''); // Utiliser l'ID comme valeur
            $choices[$label] = $value;
        }

        // Gérer le cas où aucun choix valide n'est trouvé
        if (empty($choices)) {
            $choices = ['Aucune préférence disponible' => 'none'];
        }

        // Récupérer les préférences validées pour les pré-sélections
        $checkData = $collection->find(['user' => "boutoutben", 'isValid' => true]);

        $data = [];
        foreach ($checkData as $document) {
            $data[] = (string)($document['_id'] ?? ''); // Ajouter l'ID des préférences validées
        }

        // Construire le formulaire Symfony
        $builder
            ->add('choicePreferences', ChoiceType::class, [
                'choices' => $choices, // Passer les choix formatés
                'expanded' => true,
                'multiple' => true,
                'data' => $data, // Pré-sélection des préférences validées
            ])
            ->add("submit", SubmitType::class, [
                "label" => "Enregistrer",
                "attr" => [
                    "class" => "standard-btn",
                ],
            ]);
    }

    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "usernamePreference"=>""
        ]);
    }
}
