<?php

namespace App\Tests\App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class basicTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/carpool');

        $this->assertResponseIsSuccessful();
    }
}
