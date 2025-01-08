<?php

namespace App\Tests;

use DateTime;
use Symfony\Component\Panther\PantherTestCase;

class CarpoolParticipationWithJsTest extends PantherTestCase
{
    public function testSearchCarpoolFilter()
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/carpool');

        // Search form submission
        $form = $crawler->selectButton('Rechercher')->form([
            'search_travel[startPlace]' => 'Lille',
            'search_travel[endPlace]' => 'Arras',
            'search_travel[startDate]' => (new \DateTime('2024-12-09 12:00:00'))
        ]);
        $client->submit($form);
        $crawler = $client->refreshCrawler();
        dd($client->getPageSource());

        // Filter form submission
        $filterForm = $crawler->selectButton('Filtrer')->form([
            'filter[maxPrice]' => 20,
        ]);
        $crawler = $client->submit($filterForm);

        // Assert filtered results
        $elementsWithId = $crawler->filter('#show-carpool');
        $this->assertGreaterThan(0, $elementsWithId->count(), 'There should be at least one carpool available after filtering.');
    }

}
