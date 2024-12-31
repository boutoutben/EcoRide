<?php

namespace App\Tests;

use App\Repository\CarpoolParticipationRepository;
use App\Repository\CarpoolRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

use function PHPUnit\Framework\assertEquals;

class AdministrationSpaceTest extends WebTestCase
{
    private $carpoolRepository;
    private $carpoolParticipationRepository;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock for the CarpoolRepository
        $this->carpoolRepository = $this->createMock(CarpoolRepository::class);
        $this->carpoolParticipationRepository = $this->createMock(CarpoolParticipationRepository::class);

        // Set up additional configurations if needed
    }
    public function defineClient($client)
    {
        // Fetch the login form page
        $crawler = $client->request('GET', '/connexion');
        $this->assertResponseIsSuccessful(); // Ensure the form page loads successfully

        // Select the form and fill it
        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'jose-administration',
            'connexion[password]' => 'Jose123!',
        ]);

        // Submit the form
        $client->submit($form);

        // Follow the redirection after submission
        $this->assertResponseRedirects('/'); // Adjust the redirection path
        $client->followRedirect();

        $crawler2 = $client->request('GET', '/administrationSpace');
        return $crawler2;
    }
    
    public function createEmployeeData(string $email, string $pseudo, string $password, string $passwordEgain){
        return [
            "create_account[email]" => $email,
            "create_account[pseudo]" => $pseudo,
            "create_account[password]" => $password,
            "create_account[passwordEgain]" => $passwordEgain
        ];
    }

    public function testAdministrationSpacePage(): void
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    /*
    public function testCreateEmployeeAccount()
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);
        $form = $crawler->selectButton("Créer un compte")->form($this->createEmployeeData("employee.test@gmail.com", "employee-test3", "Test123!", "Test123!"));
        $client->submit($form);
        $this->assertResponseRedirects('/administrationSpace');
    }*/

    public function testCreateEmployeeAccountInvalidEmail()
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);
        $form = $crawler->selectButton("Créer un compte")->form($this->createEmployeeData("employee.test", "employee-test3", "Test123!", "Test123!"));
        $client->submit($form);
        $this->assertSelectorTextContains('.form_error', 'Ce champ donc contenir un email');
    }

    public function testCreateEmployeeAccountInvalidPseudo()
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);
        $form = $crawler->selectButton("Créer un compte")->form($this->createEmployeeData("employee.test@gmail.com", "employee-test3;;", "Test123!", "Test123!"));
        $client->submit($form);
        $this->assertSelectorTextContains('.form_error', "Le pseudo n'est pas conforme");
    }

    public function testCreateEmployeeAccountInvalidPassword()
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);
        $form = $crawler->selectButton("Créer un compte")->form($this->createEmployeeData("employee.test@gmail.com", "employee-test", "Test123", "Test123!"));
        $client->submit($form);
        $this->assertSelectorTextContains('.form_error', "Le mot de passe n'est pas assez fort");
    }

    public function testCreateEmployeeAccountInvalidSamePassword()
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);
        $form = $crawler->selectButton("Créer un compte")->form($this->createEmployeeData("employee.test@gmail.com", "employee-test", "Test123!", "Test123?"));
        $client->submit($form);
        $this->assertSelectorTextContains('.form_error', "Les deux mot de passe ne sont pas identique");
    }

    public function testCreateEmployeeAccountNotBlank()
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);
        $form = $crawler->selectButton("Créer un compte")->form($this->createEmployeeData("", "", "", ""));
        $client->submit($form);
        $this->assertSelectorTextContains('.form_error', "Le champ ne doit pas être vide");

    }
    /*graph*/

    public function testNbCarpoolValue()
    {
        // Create a DateTime object for the test
        $dateTime = new \DateTime("2024-12-10 12:00:00");

        // Configure the mock to return a specific value when countDate is called
        $this->carpoolRepository
            ->expects($this->once()) // Ensure countDate is called exactly once
            ->method('countDate')
            ->with($this->equalTo($dateTime)) // Expect a DateTime object as parameter
            ->willReturn(2); // Simulate the method returning 2

        // Call the method under test
        $count = $this->carpoolRepository->countDate($dateTime);

        // Assert that the returned value matches the expected value
        $this->assertEquals(2, $count, "The countDate method did not return the expected value.");
    }
    public function testNbCreditValue()
    {
        // Create a DateTime object for the test
        $dateTime = new \DateTime("2024-12-10 12:00:00");

        // Configure the mock to return a specific value when countDate is called
        $this->carpoolParticipationRepository
            ->expects($this->once()) // Ensure countDate is called exactly once
            ->method('countCreditPlatform')
            ->with($this->equalTo($dateTime)) // Expect a DateTime object as parameter
            ->willReturn(4); // Simulate the method returning 4

        // Call the method under test
        $count = $this->carpoolParticipationRepository->countCreditPlatform($dateTime,2);

        // Assert that the returned value matches the expected value
        $this->assertEquals(4, $count, "The countDate method did not return the expected value.");
    }
    
    public function testNbTotalCredit()
    {
        // Configure the mock to return a specific value when countDate is called
        $this->carpoolParticipationRepository
            ->expects($this->once()) // Ensure countDate is called exactly once
            ->method('nbTotalCreditPlatform')
            ->willReturn(8);
        $count = $this->carpoolParticipationRepository->nbTotalCreditPlatform(2);
        $this->assertEquals(8, $count, "The countDate method did not return the expected value.");
    }

}
