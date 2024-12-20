<?php

namespace App\Tests;

use DateTime;
use Symfony\Component\Panther\PantherTestCase;

class CarpoolParticipationTest extends PantherTestCase
{
    public function defineClient($client)
    {
        // Step 1: Navigate to the login page
        $crawler = $client->request('GET', '/connexion');
        $client->waitFor('form');

        // Step 2: Submit the login form
        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'boutoutben',
            'connexion[password]' => 'Boutout123!',
        ]);
        $client->submit($form);
        $crawler = $client->refreshCrawler();

        // Step 3: Navigate to the carpool page
        $crawler = $client->request('GET', '/carpool');
        $client->waitFor('form[name="search_travel"]');

        // Step 4: Set datetime-local value via JavaScript
        $client->executeScript("
        document.getElementById('search_travel_startDate').value = '2024-12-08T12:00';
    ");

        // Debug the updated value
        file_put_contents('debug.html', $client->getPageSource());

        // Step 5: Set other input values
        $formTravel = $crawler->selectButton('Rechercher')->form([
            'search_travel[startPlace]' => 'Lille',
            'search_travel[endPlace]' => 'Arras',
            // Omitting 'search_travel[startDate]' since it's set via JavaScript
        ]);

        // Debug submitted values
        file_put_contents('form-debug.log', print_r($formTravel->getPhpValues(), true));

        // Step 6: Submit the form
        $client->submit($formTravel);
        $crawler = $client->refreshCrawler();

        return $crawler;
    }
    /*
    public function testCarpoolParticipationValid()
    {
        $client = self::createPantherClient(['browser' => 'firefox']);
        $crawler = $this->defineClient($client);
        // Step 1: Wait for the detail button and click it
        $client->waitFor('#detail-btn-18', 5); // Wait up to 5 seconds
        $crawler->filter('#detail-btn-18')->click();

        // Step 2: Refresh the crawler after DOM changes
        $crawler = $client->refreshCrawler();

        // Step 3: Wait for the participation button and click it
        $client->waitFor('#participation-18', 5);
        $crawler->filter('#participation-18')->click();
        $crawler = $client->refreshCrawler();
        $client->waitFor('#yesBtn-18', 5);
        $crawler->filter('#yesBtn-18')->click();

        $crawler = $client->refreshCrawler();

        $this->assertSame(
            '/carpoolDetail',
            parse_url($client->getCurrentURL(), PHP_URL_PATH),
            'The current page is not the expected carpool page.'
        );
    }*/

    public function testCarpoolParticipationWithNoValidation()
{
    $client = self::createPantherClient(['browser' => 'firefox']);
    $crawler = $this->defineClient($client);

    // Step 1: Wait for and click the detail button
    $client->waitFor('#detail-btn-23', 5);
    $crawler->filter('#detail-btn-23')->click();
    $crawler = $client->refreshCrawler();

    // Step 2: Wait for and click the participation button
    $client->waitFor('#participation-23', 5);
    $crawler->filter('#participation-23')->click();
    $crawler = $client->refreshCrawler();

    // Step 3: Wait for and click the "No" button to cancel
    $client->waitFor('#noBtn-23', 5);
    $crawler->filter('#noBtn-23')->click();
    $crawler = $client->refreshCrawler();

    // Step 4: Verify the user is redirected back to the carpool details page
    $this->assertSame(
        '/carpoolDetail',
        parse_url($client->getCurrentURL(), PHP_URL_PATH),
        'The user was not redirected to the expected carpool details page.'
    );
}

    public function testCarpoolParticipationPaticipationUserNotConnect()
    {
        $client = self::createPantherClient(['browser' => 'firefox']);
        $crawler = $client->request("GET", "/logout");

        $crawler = $client->refreshCrawler();

        $crawler = $client->request('GET', '/carpool');
        $client->waitFor('form[name="search_travel"]');

        // Step 4: Set datetime-local value via JavaScript
        $client->executeScript("
        document.getElementById('search_travel_startDate').value = '2024-12-08T12:00';
    ");
        

        // Step 5: Set other input values
        $formTravel = $crawler->selectButton('Rechercher')->form([
            'search_travel[startPlace]' => 'Lille',
            'search_travel[endPlace]' => 'Arras',
            // Omitting 'search_travel[startDate]' since it's set via JavaScript
        ]);

        // Step 6: Submit the form
        $client->submit($formTravel);
        $crawler = $client->refreshCrawler();

        // Step 1: Wait for the detail button and click it
        $client->waitFor('#detail-btn-23', 5); // Wait up to 5 seconds
        $crawler->filter('#detail-btn-23')->click();

        // Step 2: Refresh the crawler after DOM changes
        $crawler = $client->refreshCrawler();

        // Step 3: Wait for the participation button and click it
        $client->waitFor('#participation-23', 5);
        $crawler->filter('#participation-23')->click();

        $crawler = $client->refreshCrawler();


        $this->assertSame(
            '/connexion',
            parse_url($client->getCurrentURL(), PHP_URL_PATH),
            'The current page is not the expected carpool page.'
        );
        $this->assertSelectorTextContains('.form_error', "Pour participer à un covoiturage vous devez être connecté");
    }

    public function testCarpoolParticipationPaticipationDriverCase()
    {
        $client = self::createPantherClient(['browser' => 'firefox']);
        $crawler = $client->request('GET', '/connexion');
        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'test',
            'connexion[password]' => 'Test123!',
        ]);
        $client->submit($form);

        $crawler = $client->refreshCrawler();

        $crawler = $client->request('GET', '/carpool');
        $client->waitFor('form[name="search_travel"]');

        // Step 4: Set datetime-local value via JavaScript
        $client->executeScript("
        document.getElementById('search_travel_startDate').value = '2024-12-08T12:00';
    ");


        // Step 5: Set other input values
        $formTravel = $crawler->selectButton('Rechercher')->form([
            'search_travel[startPlace]' => 'Lille',
            'search_travel[endPlace]' => 'Arras',
            // Omitting 'search_travel[startDate]' since it's set via JavaScript
        ]);

        // Step 6: Submit the form
        $client->submit($formTravel);
        $crawler = $client->refreshCrawler();

        // Step 1: Wait for the detail button and click it
        $client->waitFor('#detail-btn-23', 5); // Wait up to 5 seconds
        $crawler->filter('#detail-btn-23')->click();

        // Step 2: Refresh the crawler after DOM changes
        $crawler = $client->refreshCrawler();

        // Step 3: Wait for the participation button and click it
        $client->waitFor('#participation-23', 5);
        $crawler->filter('#participation-23')->click();

        $crawler = $client->refreshCrawler();


        $this->assertSame(
            '/carpoolDetail',
            parse_url($client->getCurrentURL(), PHP_URL_PATH),
            'The current page is not the expected carpool page.'
        );
        $this->assertSelectorTextContains('.form_error', "Seul les passagers peut participer à un covoiturage, mais vous pouvais être les deux en même temps en cochant la case");
    }
}
