<?php 

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Date;

class UserSpaceInfoDriverTest extends PantherTestCase
{
    //new car

    public function defineClient($client)
    {
        // Fetch the login form page
        $crawler = $client->request('GET', '/connexion'); // Navigate to the login page

        // Wait for the form to be visible (in case the page takes time to load)
        $client->waitFor('form'); // Wait for the form to appear

        // Select the form and fill it
        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'boutoutben',
            'connexion[password]' => 'Boutout123!',
        ]);

        // Submit the form
        $client->submit($form);

        

        // Fetch the page after login (user space)
        $crawler2 = $client->request("GET","/userSpace"); // Get the crawler for the current page

        return $crawler2; // Return the new crawler
    }

    public function newCarData(string $licensePlate, string $firstImmatriculation, string $model,string $color, int $nbPassenger){
        return [
            'new_car[licensePlate]' => $licensePlate,
            'new_car[firstImmatriculation]' => $firstImmatriculation,
            'new_car[model]' => $model,
            'new_car[color]' => $color,
            'new_car[nbPassenger]' => $nbPassenger,
        ];
    }/*
    public function testNewCarValidData()
    {
        $client = self::createPantherClient(['browser' => 'firefox']);

        // Log in and get the user space page
        $crawler = $this->defineClient($client);

        // Wait for the button to appear
        $client->waitFor('#plus-btn', 5); // Wait up to 60 seconds

        // Click the button
        $crawler->filter('#plus-btn')->click();


        // Fill out the form and submit it
        $form = $crawler->selectButton('new_car[submit]')->form($this->newCarData("AB-123-CD", "2024-11-25", "xs", "vert", 4));
        $client->submit($form);
        $this->assertEquals($form["new_car[model]"],"xs");
        
    }*/
    public function testNewCarInvalidPlateLicense()
    {
        $client = self::createPantherClient(['browser' => 'firefox']);

        // Log in and get the user space page
        $crawler = $this->defineClient($client);

        // Wait for the button to appear
        $client->waitFor('#plus-btn', 5); // Wait up to 60 seconds

        // Click the button
        $crawler->filter('#plus-btn')->click();

        
        // Fill out the form and submit it
        $form = $crawler->selectButton('new_car[submit]')->form($this->newCarData("AB-123-C", "2024-11-25", "xs", "vert", 4));
        $client->submit($form);
        $crawler = $client->refreshCrawler();
        $client->waitFor('#plus-btn', 5); // Wait up to 60 seconds

        // Click the button
        $crawler->filter('#plus-btn')->click();
        $this->assertSelectorTextContains('.form_error', "La plaque d'immatriculation n'est pas conforme");
    }
    public function testNewCarInvalidModel()
    {
        $client = self::createPantherClient(['browser' => 'firefox']);

        // Log in and get the user space page
        $crawler = $this->defineClient($client);

        // Wait for the button to appear
        $client->waitFor('#plus-btn', 5); // Wait up to 60 seconds

        // Click the button
        $crawler->filter('#plus-btn')->click();


        // Fill out the form and submit it
        $form = $crawler->selectButton('new_car[submit]')->form($this->newCarData("AB-123-CD", "2024-11-25", "xs((", "vert", 4));
        $client->submit($form);
        $crawler = $client->refreshCrawler();
        $client->waitFor('#plus-btn', 5); // Wait up to 60 seconds

        // Click the button
        $crawler->filter('#plus-btn')->click();
        $this->assertSelectorTextContains('.form_error', "Le modèle n'est pas conforme et le nombre de caractère doit être compris entre 2 et 50");
    }
    public function testNewCarInvalidColor()
    {
        $client = self::createPantherClient(['browser' => 'firefox']);

        // Log in and get the user space page
        $crawler = $this->defineClient($client);

        // Wait for the button to appear
        $client->waitFor('#plus-btn', 5); // Wait up to 60 seconds

        // Click the button
        $crawler->filter('#plus-btn')->click();


        // Fill out the form and submit it
        $form = $crawler->selectButton('new_car[submit]')->form($this->newCarData("AB-123-CD", "2024-11-25", "xs", "vert((", 4));
        $client->submit($form);
        $crawler = $client->refreshCrawler();
        $client->waitFor('#plus-btn', 5); // Wait up to 60 seconds

        // Click the button
        $crawler->filter('#plus-btn')->click();
        $this->assertSelectorTextContains('.form_error', "La couleur n'est pas conforme et le nombre de caractère doit être compris entre 2 et 50");
    }
    public function testNewCarInvalidNbPassenger()
    {
        $client = self::createPantherClient(['browser' => 'firefox']);

        // Log in and get the user space page
        $crawler = $this->defineClient($client);

        // Wait for the button to appear
        $client->waitFor('#plus-btn', 5); // Wait up to 60 seconds

        // Click the button
        $crawler->filter('#plus-btn')->click();


        // Fill out the form and submit it
        $form = $crawler->selectButton('new_car[submit]')->form($this->newCarData("AB-123-CD", "2024-11-25", "xs", "vert", 0));
        $client->submit($form);
        $crawler = $client->refreshCrawler();
        $client->waitFor('#plus-btn', 5); // Wait up to 60 seconds

        // Click the button
        $crawler->filter('#plus-btn')->click();
        $this->assertSelectorTextContains('.form_error', "Le nombre de passager doit être supérieur à 0");
    }
    public function testNewCarNotBlank()
    {
        $client = self::createPantherClient(['browser' => 'firefox']);

        // Log in and get the user space page
        $crawler = $this->defineClient($client);

        // Wait for the button to appear
        $client->waitFor('#plus-btn', 5); // Wait up to 60 seconds

        // Click the button
        $crawler->filter('#plus-btn')->click();


        // Fill out the form and submit it
        $form = $crawler->selectButton('new_car[submit]')->form($this->newCarData("", "2024-11-25", "vx", "vert", 2));
        $client->submit($form);
        $crawler = $client->refreshCrawler();
        $client->waitFor('#plus-btn', 5); // Wait up to 60 seconds

        // Click the button
        $crawler->filter('#plus-btn')->click();
        $this->assertSelectorTextContains('.form_error', "Le champ ne peut pas être vide");
    }
}