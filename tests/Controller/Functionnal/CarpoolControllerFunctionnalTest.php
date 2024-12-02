<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CarpoolControllerFunctionnalTest extends WebTestCase
{
    public function testCarpoolPage()
    {
        $client = static::createClient();
        $client->request("GET", "/carpool");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testFormSubmissionWithValidData()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/carpool');
        $form = $crawler->selectButton('Rechercher')->form([
            'search_travel[startPlace]' => 'Lille',
            'search_travel[endPlace]' => 'Paris',
            'search_travel[startDate]' => '2012-02-01 07:00:00',
        ]);

        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testFormSubmissionWithInvalidData()
    {
        $client = static::createClient();

        // Request the page with the form
        $crawler = $client->request('POST', '/carpool');

        // Select the form and fill in invalid data (empty values)
        $form = $crawler->selectButton('Rechercher')->form([
            'search_travel[startPlace]' => '',
            'search_travel[endPlace]' => '',
            'search_travel[startDate]' => '',
        ]);

        // Submit the form
        $client->submit($form);

        // Assert that the form was returned and not redirected (HTTP_OK means it's still the same page)
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        // Assert that the form contains validation error messages
        $this->assertSelectorTextContains('.form_error', 'Le champ ne peut pas être vide');  // Adjust this to match your error message
    }

}