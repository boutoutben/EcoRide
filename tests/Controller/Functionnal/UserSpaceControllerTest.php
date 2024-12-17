<?php

namespace App\Tests;

use App\Entity\Carpool;
use App\Repository\CarpoolRepository;
use phpDocumentor\Reflection\PseudoTypes\True_;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpClient\HttpClient;


class UserSpaceControllerTest extends WebTestCase 
{
    private MockObject $carpoolRepository;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock for CarpoolRepository
        $this->carpoolRepository = $this->createMock(CarpoolRepository::class);
    }
    public function defineClient($client)
    {
        // Fetch the login form page
        $crawler = $client->request('GET', '/connexion');
        $this->assertResponseIsSuccessful(); // Ensure the form page loads successfully

        // Select the form and fill it
        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'test4',
            'connexion[password]' => 'Test123?',
        ]);

        // Submit the form
        $client->submit($form);

        // Follow the redirection after submission
        $this->assertResponseRedirects('/'); // Adjust the redirection path
        $client->followRedirect();

        $crawler2 = $client->request('GET', '/userSpace');
        return $crawler2;
    }



    public function formUserSpace(string $userType, string $name, string $surname, string $email, string $phone, string $pseudo, string $password, string $newPassword)
    {
        return [
            'user_profile[userType]' => $userType,
            "user_profile[name]" => $name,
            "user_profile[surname]" => $surname,
            "user_profile[email]" => $email,
            "user_profile[phone]" => $phone,
            "user_profile[pseudo]" => $pseudo,
            "user_profile[password]" => $password,
            "user_profile[newPassword]" => $newPassword
        ];
    }
    public function testUserSpacePage(): void
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);
        $this->assertResponseIsSuccessful(); // Ensure the user space page loads successfully

        // Optional: Assert the content of the user space page
        $this->assertSelectorTextContains('#profile h1', 'Profile');
    }
    public function testUserPasswordValidData(): void
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);
        
        $form = $crawler->selectButton("Enregistrer")->form(
            $this->formUserSpace("Passager","testName","testSurname","test@gmail.com","06 05 05 05 05","test123","Test123?","Test123?")
        );
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    public function testUserPasswordValidDataWithoutPasswordAndPhone(): void
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);

        $form = $crawler->selectButton("Enregistrer")->form(
            $this->formUserSpace("Passager", "testName", "testSurname", "test@gmail.com", "", "test123", "", "")
        );
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testUserPasswordValidDataNotBlank(): void
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);

        $form = $crawler->selectButton("Enregistrer")->form(
            $this->formUserSpace("Passager", "", "", "", "06 05 05 05 05", "", "Test123?", "Test123?")
        );
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('.form_error', "Le champ ne doit pas être vide");
    }
    public function testUserPasswordValidDataMinLength(): void
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);

        $form = $crawler->selectButton("Enregistrer")->form(
            $this->formUserSpace("Passager", "w", "f", "d", "06 05 05 05 05", "f", "Test123?", "Test123?")
        );
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('.form_error', "Le nombre de caractère est trop faible");
    }
    public function testUserPasswordValidDataMaxLength(): void
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);

        $form = $crawler->selectButton("Enregistrer")->form(
            $this->formUserSpace("Passager", "wffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff", "wffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff", "wffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff", "06 05 05 05 05", "wffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff", "wffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff", "wffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff")
        );
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('.form_error', "Il y a trop de caractère, il en faut maximum 50");
    }
    public function testUserPasswordInvalidTextRegex(): void
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);

        $form = $crawler->selectButton("Enregistrer")->form(
            $this->formUserSpace("Passager", "testName;;", "testSurname;;", "test@gmail.com", "", "test123;;", "", "")
        );
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('.form_error', "Le text n'est pas conforme");
    }

    public function testUserPasswordInvalidEmailRegex(): void
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);

        $form = $crawler->selectButton("Enregistrer")->form(
            $this->formUserSpace("Passager", "testName", "testSurname", "testgmail.com", "", "test123", "", "")
        );
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('.form_error', "Ce champ donc contenir un email");
    }
    public function testUserPasswordInvalidTelephoneRegex(): void
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);

        $form = $crawler->selectButton("Enregistrer")->form(
            $this->formUserSpace("Passager", "testName", "testSurname", "test@gmail.com", "0648608090", "test123", "", "")
        );
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('.form_error', "Le numéro doit être sous cette forme: 06 00 00 00 00");
    }

    public function testUserPasswordInvalidPasswordRegex(): void
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);

        $form = $crawler->selectButton("Enregistrer")->form(
            $this->formUserSpace("Passager", "testName", "testSurname", "test@gmail.com", "", "test123", "test123", "test123")
        );
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('.form_error', "Mot de passe non conforme");
    }

    public function testUserSpaceWithWrongCurrentPassword(): void
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);

        $form = $crawler->selectButton("Enregistrer")->form(
            $this->formUserSpace("Passager", "testName", "testSurname", "test@gmail.com", "", "test123", "Test123!", "Test123!")
        );
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('.form_error', "Le mot de passe saisie n'est pas correct");
    }

    public function testUserSpaceWithOneOfTheTwoPassword(): void
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);

        $form = $crawler->selectButton("Enregistrer")->form(
            $this->formUserSpace("Passager", "testName", "testSurname", "test@gmail.com", "", "test123", "Test123!", "")
        );
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('.form_error', "Si vous souhaitez mettre à jour le mot de passe, il faut remplir les deux champs");
    }

    //travel outpute
    
    /*public function testTravelOutputeValidData()
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);
        $form = $crawler->selectButton("create_carpool[submit]")->form([
            "create_carpool[startPlace]" => "Lille",
            "create_carpool[endPlace]" => "Arras",
            "create_carpool[startDate]" => "2024-12-09 12:00:00",
            "create_carpool[endDate]" => "2024-12-09 13:00:00",
            "create_carpool[carChoice]" => 14,
            "create_carpool[credit]" => 20
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(302); // Ensure the redirect occurs
        $this->assertTrue($client->getResponse()->isRedirect('/userSpace#travelOutpute'));
    }*/
    public function testTravelOutputeInvalidStartPlace()
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);
        $form = $crawler->selectButton("create_carpool[submit]")->form([
            "create_carpool[startPlace]" => "Lille((",
            "create_carpool[endPlace]" => "Arras",
            "create_carpool[startDate]" => "2024-12-09 12:00:00",
            "create_carpool[endDate]" => "2024-12-09 13:00:00",
            "create_carpool[carChoice]" => 14,
            "create_carpool[credit]" => 20
        ]);
        $client->submit($form);
        $this->assertSelectorTextContains('.form_error', "Le lieu de départ n'est pas conforme et le nombre de caractère doit être compris entre 2 et 50");
    }
    public function testTravelOutputeInvalidEndPlace()
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);
        $form = $crawler->selectButton("create_carpool[submit]")->form([
            "create_carpool[startPlace]" => "Lille",
            "create_carpool[endPlace]" => "Arras((",
            "create_carpool[startDate]" => "2024-12-09 12:00:00",
            "create_carpool[endDate]" => "2024-12-09 13:00:00",
            "create_carpool[carChoice]" => 14,
            "create_carpool[credit]" => 20
        ]);
        $client->submit($form);
        $this->assertSelectorTextContains('.form_error', "Le lieu d'arriver n'est pas conforme et le nombre de caractère doit être compris entre 2 et 50");
    }
    public function testTravelOutputeInvalidCredit()
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);
        $form = $crawler->selectButton("create_carpool[submit]")->form([
            "create_carpool[startPlace]" => "Lille",
            "create_carpool[endPlace]" => "Arras",
            "create_carpool[startDate]" => "2024-12-09 12:00:00",
            "create_carpool[endDate]" => "2024-12-09 13:00:00",
            "create_carpool[carChoice]" => 14,
            "create_carpool[credit]" => 0
        ]);
        $client->submit($form);
        $this->assertSelectorTextContains('.form_error', "Le crédit doit être supérieur ou égal à 2");
    }
    public function testTravelOutputeNotBlank()
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);
        $form = $crawler->selectButton("create_carpool[submit]")->form([
            "create_carpool[startPlace]" => "",
            "create_carpool[endPlace]" => "",
            "create_carpool[startDate]" => "2024-12-09 12:00:00",
            "create_carpool[endDate]" => "2024-12-09 13:00:00",
            "create_carpool[carChoice]" => 14,
            "create_carpool[credit]" => 3
        ]);
        $client->submit($form);
        $this->assertSelectorTextContains('.form_error', "Le champ ne peut pas être vide");
    }
    public function testTravelOutputeValidDataWithNewCar()
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);
        $form = $crawler->selectButton("create_carpool[submit]")->form([
            "create_carpool[startPlace]" => "Lille",
            "create_carpool[endPlace]" => "Arras",
            "create_carpool[startDate]" => "2024-12-09 12:00:00",
            "create_carpool[endDate]" => "2024-12-09 13:00:00",
            "create_carpool[carChoice]" => 'other',
            "create_carpool[credit]" => 20,
            "create_carpool[licensePlate]" => "XX-400-XX",
            "create_carpool[firstImmatriculation]" => "2020-12-01",
            "create_carpool[model]" => "xf",
            "create_carpool[color]" => "rouge",
            "create_carpool[nbPassenger]" => 4
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(302); // Ensure the redirect occurs
        $this->assertTrue($client->getResponse()->isRedirect('/userSpace#travelOutpute'));
    }
    public function testTravelOutputeInvalideLisencePlaceWithNewCar()
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);
        $form = $crawler->selectButton("create_carpool[submit]")->form([
            "create_carpool[startPlace]" => "Lille",
            "create_carpool[endPlace]" => "Arras",
            "create_carpool[startDate]" => "2024-12-09 12:00:00",
            "create_carpool[endDate]" => "2024-12-09 13:00:00",
            "create_carpool[carChoice]" => 'other',
            "create_carpool[credit]" => 20,
            "create_carpool[licensePlate]" => "XX-400-X",
            "create_carpool[firstImmatriculation]" => "2020-12-01",
            "create_carpool[model]" => "xf",
            "create_carpool[color]" => "rouge",
            "create_carpool[nbPassenger]" => 4
        ]);
        $client->submit($form);
        $this->assertSelectorTextContains('.form_error', "La plaque d'immatriculation n'est pas conforme");
    }
    public function testTravelOutputeInvalideModelWithNewCar()
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);
        $form = $crawler->selectButton("create_carpool[submit]")->form([
            "create_carpool[startPlace]" => "Lille",
            "create_carpool[endPlace]" => "Arras",
            "create_carpool[startDate]" => "2024-12-09 12:00:00",
            "create_carpool[endDate]" => "2024-12-09 13:00:00",
            "create_carpool[carChoice]" => 'other',
            "create_carpool[credit]" => 20,
            "create_carpool[licensePlate]" => "XX-400-XX",
            "create_carpool[firstImmatriculation]" => "2020-12-01",
            "create_carpool[model]" => "x((f",
            "create_carpool[color]" => "rouge",
            "create_carpool[nbPassenger]" => 4
        ]);
        $client->submit($form);
        $this->assertSelectorTextContains('.form_error', "Le modèle n'est pas conforme et le nombre de caractère doit être compris entre 2 et 50");
    }
    public function testTravelOutputeInvalideColorWithNewCar()
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);
        $form = $crawler->selectButton("create_carpool[submit]")->form([
            "create_carpool[startPlace]" => "Lille",
            "create_carpool[endPlace]" => "Arras",
            "create_carpool[startDate]" => "2024-12-09 12:00:00",
            "create_carpool[endDate]" => "2024-12-09 13:00:00",
            "create_carpool[carChoice]" => 'other',
            "create_carpool[credit]" => 20,
            "create_carpool[licensePlate]" => "XX-400-XX",
            "create_carpool[firstImmatriculation]" => "2020-12-01",
            "create_carpool[model]" => "xf",
            "create_carpool[color]" => "rouge()",
            "create_carpool[nbPassenger]" => 4
        ]);
        $client->submit($form);
        $this->assertSelectorTextContains('.form_error', "La couleur n'est pas conforme et le nombre de caractère doit être compris entre 2 et 50");
    }
    public function testTravelOutputeInvalideNbPassengerPositifWithNewCar()
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);
        $form = $crawler->selectButton("create_carpool[submit]")->form([
            "create_carpool[startPlace]" => "Lille",
            "create_carpool[endPlace]" => "Arras",
            "create_carpool[startDate]" => "2024-12-09 12:00:00",
            "create_carpool[endDate]" => "2024-12-09 13:00:00",
            "create_carpool[carChoice]" => 'other',
            "create_carpool[credit]" => 20,
            "create_carpool[licensePlate]" => "XX-400-XX",
            "create_carpool[firstImmatriculation]" => "2020-12-01",
            "create_carpool[model]" => "xf",
            "create_carpool[color]" => "rouge",
            "create_carpool[nbPassenger]" => 0
        ]);
        $client->submit($form);
        $this->assertSelectorTextContains('.form_error', "Le nombre de passager doit être supérieur à 0");
    }
    public function testTravelOutputeInvalideNbPassengerLessThanWithNewCar()
    {
        $client = static::createClient();
        $crawler = $this->defineClient($client);
        $form = $crawler->selectButton("create_carpool[submit]")->form([
            "create_carpool[startPlace]" => "Lille",
            "create_carpool[endPlace]" => "Arras",
            "create_carpool[startDate]" => "2024-12-09 12:00:00",
            "create_carpool[endDate]" => "2024-12-09 13:00:00",
            "create_carpool[carChoice]" => 'other',
            "create_carpool[credit]" => 20,
            "create_carpool[licensePlate]" => "XX-400-XX",
            "create_carpool[firstImmatriculation]" => "2020-12-01",
            "create_carpool[model]" => "xf",
            "create_carpool[color]" => "rouge",
            "create_carpool[nbPassenger]" => 10
        ]);
        $client->submit($form);
        $this->assertSelectorTextContains('.form_error', "Le nombre de passager doit être inférieur à 10");
    }
    public function testTravelOutputeInvalideIsEcologique()
    {
        $carpool = $this->getContainer()->get('doctrine')->getRepository(Carpool::class)->findBy(["isEcologique"=>true]);
        foreach($carpool as $carpool)
        {
            $this->assertEquals($carpool->getCar()->getEnergie(), "Electrique");
        }
    }

    

}