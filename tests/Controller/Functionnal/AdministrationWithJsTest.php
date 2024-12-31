<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class  AdministrationWithJsTest extends PantherTestCase
{
    public function defineClient($client)
    {
        // Fetch the login form page
        $crawler = $client->request('GET', '/connexion');

        // Select the form and fill it
        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'jose-administration',
            'connexion[password]' => 'Jose123!',
        ]);

        // Submit the form
        $client->submit($form);

        $crawler2 = $client->request('GET', '/administrationSpace');
        return $crawler2;
    }

    public function testSuspendUser(): void
    {
        $client = self::createPantherClient(['browser' => 'firefox']);
        $crawler = $this->defineClient($client);

        $client->waitFor('#suspend-1');
        $button = $crawler->filter('#suspend-1');
        $this->assertCount(1, $button, 'The suspend button was not found.');
        $button->click();

        // Allow time for redirection or DOM update

        $this->assertSame(
            '/administrationSpace',
            parse_url($client->getCurrentURL(), PHP_URL_PATH),
            'The user was not redirected to the expected administration page.'
        );
    }
}
