<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class OpinionControllerTest extends PantherTestCase
{
    /*
    public function testValidOpinion(): void
    {
        // Use Firefox as the browser for this test
        $client = self::createPantherClient(['browser' => 'firefox']);

        // Navigate to the login page and fill the login form
        $crawler = $client->request('GET', '/connexion');
        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'employee-test',
            'connexion[password]' => 'Employee123!',
        ]);
        $client->submit($form);

        // Navigate to the employee space
        $crawler = $client->request('GET', '/employeeSpace');

        // Wait for the "validBtn-1" button to appear
        $client->waitFor('#validBtn-1',5);

        // Click the button
        $crawler->filter('#validBtn-1')->click();

        // Assert that the user is redirected to the expected URL
        $this->assertSame(
            '/employeeSpace',
            parse_url($client->getCurrentURL(), PHP_URL_PATH),
            'The user was not redirected to the expected employee page.'
        );
    }
    public function testNonValidOpinion(): void
    {
        // Use Firefox as the browser for this test
        $client = self::createPantherClient(['browser' => 'firefox']);

        // Navigate to the login page and fill the login form
        $crawler = $client->request('GET', '/connexion');
        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'employee-test',
            'connexion[password]' => 'Employee123!',
        ]);
        $client->submit($form);

        // Navigate to the employee space
        $crawler = $client->request('GET', '/employeeSpace');

        // Wait for the "validBtn-1" button to appear
        $client->waitFor('#noValidBtn-2', 5);

        // Click the button
        $crawler->filter('#noValidBtn-2')->click();

        // Assert that the user is redirected to the expected URL
        $this->assertSame(
            '/employeeSpace',
            parse_url($client->getCurrentURL(), PHP_URL_PATH),
            'The user was not redirected to the expected employee page.'
        );
    }*/

    public function testShowOpinion(): void
    {
        // Use Firefox as the browser for this test
        $client = self::createPantherClient(['browser' => 'firefox']);

        // Navigate to the login page and fill the login form
        $crawler = $client->request('GET', '/connexion');
        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'boutoutben',
            'connexion[password]' => 'Boutout123!',
        ]);
        $client->submit($form);
        $crawler = $client->request("GET","/userSpace");
        $client->waitFor('#detail-btn-44');
        $crawler->filter('#detail-btn-44')->click();
        $crawler = $client->refreshCrawler();

        $client->waitFor("#opinion_2");
        $crawler->filter("#opinion_2")->click();
        $this->assertSame(
            '/opinion',
            parse_url($client->getCurrentURL(), PHP_URL_PATH),
            'The user was not redirected to the expected employee page.'
        );
    }

}
