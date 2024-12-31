<?php 

namespace App\Tests;

use App\Entity\Carpool;
use Symfony\Component\Panther\PantherTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Date;

class UserSpaceInfoDriverTest extends PantherTestCase
{

    public function defineClient($client)
    {
        // Fetch the login form page
        $crawler = $client->request('GET', '/connexion'); // Navigate to the login page

        // Wait for the form to be visible (in case the page takes time to load)
        $client->waitFor('form'); // Wait for the form to appear

        // Select the form and fill it
        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'test',
            'connexion[password]' => 'Test123!',
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
    }
    
    public function testNewCarValidData()
    {
        $client = self::createPantherClient(['browser' => 'firefox']);
        // Log in and get the user space page
        $crawler = $this->defineClient($client);

        // Wait for the button to appear
        $client->waitFor('#plus-btn', 5); // Wait up to 60 seconds

        // Click the button
        $crawler->filter('#plus-btn')->click();

        $crawler = $client->refreshCrawler();
        // Fill out the form and submit it
        $form = $crawler->selectButton('new_car[submit]')->form($this->newCarData("AB-123-CD", "2024-11-25", "xs", "vert", 4));
        $client->submit($form);
        $this->assertSame(
            '/userSpace',
            parse_url($client->getCurrentURL(), PHP_URL_PATH),
            'The user was not redirected to the expected carpool details page.'
        );
        
    }
    
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

    public function testEditCar()
    {
        $client = self::createPantherClient(['browser' => 'firefox']);

        // Log in and get the user space page
        $crawler = $this->defineClient($client);

        // Wait for the edit button to appear
        $client->waitFor('#edit1', 5);
        $crawler->filter('#edit1')->click();

        // Fill out the form and submit it
        $form = $crawler->selectButton('edit_form[submit]')->form([
            "edit_form[licensePlate]" => "AB-123-CD",
            "edit_form[firstRegistration]" => "2022-11-30",
            "edit_form[model]" => "xz",
            "edit_form[color]" => "gris",
            "edit_form[nbPassenger]" => 5
        ]);
        $client->submit($form);

        // Refresh the crawler and check the current URL
        $crawler = $client->refreshCrawler();
        $this->assertSame(
            '/userSpace',
            parse_url($client->getCurrentURL(), PHP_URL_PATH),
            'The current page is not the expected userSpace page.'
        );
    }
    public function testEditCarInvalidLisencePlate()
    {
        $client = self::createPantherClient(['browser' => 'firefox']);

        // Log in and get the user space page
        $crawler = $this->defineClient($client);

        // Wait for the edit button to appear
        $client->waitFor('#edit1', 5);
        $crawler->filter('#edit1')->click();

        // Fill out the form and submit it
        $form = $crawler->selectButton('edit_form[submit]')->form([
            "edit_form[licensePlate]" => "AB-123-C",
        ]);
        $client->submit($form);

        // Refresh the crawler and check the current URL
        $crawler = $client->refreshCrawler();
        $this->assertSame(
            '/userSpace',
            parse_url($client->getCurrentURL(), PHP_URL_PATH),
            'The current page is not the expected userSpace page.'
        );
        $client->waitFor('#edit1', 5);
        $crawler->filter('#edit1')->click();
        $this->assertSelectorTextContains('.form_error', "La plaque d'immatriculation n'est pas conforme");
    }

    public function testEditCarInvalidModel()
    {
        $client = self::createPantherClient(['browser' => 'firefox']);

        // Log in and get the user space page
        $crawler = $this->defineClient($client);

        // Wait for the edit button to appear
        $client->waitFor('#edit1', 5);
        $crawler->filter('#edit1')->click();

        // Fill out the form and submit it
        $form = $crawler->selectButton('edit_form[submit]')->form([
            "edit_form[model]" => "xz;;"
        ]);
        $client->submit($form);

        // Refresh the crawler and check the current URL
        $crawler = $client->refreshCrawler();
        $this->assertSame(
            '/userSpace',
            parse_url($client->getCurrentURL(), PHP_URL_PATH),
            'The current page is not the expected userSpace page.'
        );
        $client->waitFor('#edit1', 5);
        $crawler->filter('#edit1')->click();
        $this->assertSelectorTextContains('.form_error', "Le modèle n'est pas conforme et le nombre de caractère doit être compris entre 2 et 50");
    }
    public function testEditCarInvalidColor()
    {
        $client = self::createPantherClient(['browser' => 'firefox']);

        // Log in and get the user space page
        $crawler = $this->defineClient($client);

        // Wait for the edit button to appear
        $client->waitFor('#edit1', 5);
        $crawler->filter('#edit1')->click();

        // Fill out the form and submit it
        $form = $crawler->selectButton('edit_form[submit]')->form([
            "edit_form[color]" => "gris;;",
        ]);
        $client->submit($form);

        // Refresh the crawler and check the current URL
        $crawler = $client->refreshCrawler();
        $this->assertSame(
            '/userSpace',
            parse_url($client->getCurrentURL(), PHP_URL_PATH),
            'The current page is not the expected userSpace page.'
        );
        $client->waitFor('#edit1', 5);
        $crawler->filter('#edit1')->click();
        $this->assertSelectorTextContains('.form_error', "La couleur n'est pas conforme et le nombre de caractère doit être compris entre 2 et 50");
    }

    public function testEditCarInvalidZeroNbPassenger()
    {
        $client = self::createPantherClient(['browser' => 'firefox']);

        // Log in and get the user space page
        $crawler = $this->defineClient($client);

        // Wait for the edit button to appear
        $client->waitFor('#edit1', 5);
        $crawler->filter('#edit1')->click();

        // Fill out the form and submit it
        $form = $crawler->selectButton('edit_form[submit]')->form([
            "edit_form[nbPassenger]" => 0
        ]);
        $client->submit($form);

        // Refresh the crawler and check the current URL
        $crawler = $client->refreshCrawler();
        $this->assertSame(
            '/userSpace',
            parse_url($client->getCurrentURL(), PHP_URL_PATH),
            'The current page is not the expected userSpace page.'
        );
        $client->waitFor('#edit1', 5);
        $crawler->filter('#edit1')->click();
        $this->assertSelectorTextContains('.form_error', "Le nombre de passager doit être supérieur à 0");
    }

    public function testEditCarInvalidTooMuchPassenger()
    {
        $client = self::createPantherClient(['browser' => 'firefox']);

        // Log in and get the user space page
        $crawler = $this->defineClient($client);

        // Wait for the edit button to appear
        $client->waitFor('#edit1', 5);
        $crawler->filter('#edit1')->click();

        // Fill out the form and submit it
        $form = $crawler->selectButton('edit_form[submit]')->form([
            "edit_form[nbPassenger]" => 10
        ]);
        $client->submit($form);

        // Refresh the crawler and check the current URL
        $crawler = $client->refreshCrawler();
        $this->assertSame(
            '/userSpace',
            parse_url($client->getCurrentURL(), PHP_URL_PATH),
            'The current page is not the expected userSpace page.'
        );
        $client->waitFor('#edit1', 5);
        $crawler->filter('#edit1')->click();
        $this->assertSelectorTextContains('.form_error', "Le nombre de passager doit être inférieur à 10");
    }

    /*userTravel*/
    
    public function testUserTravelParticipationErrorMessage()
    {
        $client = self::createPantherClient(['browser' => 'firefox']);
        $crawler = $client->request('GET', '/connexion');

        $client->waitFor('form'); // Wait for login form

        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'boutoutben',
            'connexion[password]' => 'Boutout123!',
        ]);

        $client->submit($form);

        $crawler = $client->request('GET', '/userSpace');
        $client->waitFor('#detail-btn-1', 10); // Wait for button to load

        $this->assertGreaterThan(0, $crawler->filter('#detail-btn-1')->count(), 'Button not found!');
        $crawler->filter('#detail-btn-1')->click();
        $crawler = $client->refreshCrawler();
        $client->waitFor('#participation-1', 10); // Wait for button to load

        $this->assertGreaterThan(0, $crawler->filter('#participation-1')->count(), 'Button not found!');
        $crawler->filter('#participation-1')->click();
        // Wait for the error message to appear
        $client->waitFor('.form_error', 10);
        $this->assertSelectorTextContains('.form_error', 'Vous participez déjà au covoiturage');
    }

    /* history*/
    /*
    public function testCancelleCarpool()
    {
        $client = self::createPantherClient(['browser' => 'firefox']);

        $crawler = $client->request('GET', '/connexion');

        $client->waitFor('form'); // Wait for login form

        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'test4',
            'connexion[password]' => 'Test123?',
        ]);
        $client->submit($form);
        $crawler = $client->request('GET', '/userSpace');
        $this->assertGreaterThan(0, $crawler->filter('#cancelle-15')->count(), 'The element with the given ID does not exist.');
        $client->waitFor("#cancelle-15", 15);
        $crawler->filter("#cancelle-15")->click();
        $crawler = $client->refreshCrawler();
        $client->waitFor("#yesBtn-15",10);
        $crawler->filter("#yesBtn-15")->click();
        $crawler = $client->refreshCrawler();
        $this->assertEquals(0, $crawler->filter('#cancelle-15')->count(), 'The element with the given ID does not exist.');
    }*/
    public function testCancelleCarpoolNoBtn()
    {
        $client = self::createPantherClient(['browser' => 'firefox']);

        $crawler = $client->request('GET', '/connexion');

        $client->waitFor('form'); // Wait for login form

        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'test4',
            'connexion[password]' => 'Test123?',
        ]);
        $client->submit($form);
        $crawler = $client->request('GET', '/userSpace');
        $client->waitFor("#cancelle-20", 15);
        $crawler->filter("#cancelle-20")->click();
        $crawler = $client->refreshCrawler();
        $client->waitFor("#noBtn-20", 10);
        $crawler->filter("#noBtn-20")->click();
        $crawler = $client->refreshCrawler();
        $this->assertEquals(1, $crawler->filter('#cancelle-20')->count(), 'The element with the given ID does not exist.');
    }
    /*
    public function testStartBtn()
    {
        $client = self::createPantherClient(['browser' => 'firefox']);

        $crawler = $client->request('GET', '/connexion');

        $client->waitFor('form'); // Wait for login form

        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'test4',
            'connexion[password]' => 'Test123?',
        ]);
        $client->submit($form);
        $crawler = $client->request('GET', '/userSpace');
        $carpool = $this->getContainer()->get('doctrine')->getRepository(Carpool::class)->findOneBy(["id" => 43]);
        $client->waitFor("#startBtn-19",10);
        $crawler->filter("#startBtn-19")->click();
        
        $this->assertSame(
            '/userSpace',
            parse_url($client->getCurrentURL(), PHP_URL_PATH),
            'The user was not redirected to the expected userSpace page.'
        );
        // For the start bool, look at the database
    }*/
    /*
    public function testCarpoolOpinion()
    {
        $client = self::createPantherClient(['browser' => 'firefox']);
        $crawler = $client->request('GET', '/connexion');

        $client->waitFor('form'); // Wait for login form

        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'test4',
            'connexion[password]' => 'Test123?',
        ]);
        $client->submit($form);
        $crawler = $client->request('GET', '/userSpace');
        for($i=0;$i<2;$i++)
        {
            $client->waitFor("#startBtn-1", 10);
            $crawler->filter("#startBtn-1")->click();
            
        }
        $crawler = $client->request('GET', '/connexion');

        $client->waitFor('form'); // Wait for login form

        $form = $crawler->selectButton('Connexion')->form([
                'connexion[pseudo]' => 'boutoutben',
                'connexion[password]' => 'Boutout123!',
            ]);
        $client->submit($form);

        // Request the next page after login
        $crawler = $client->request('GET', '/userSpace');

        // Fill and submit the opinion form
        $opinionForm = $crawler->selectButton("Valider")->form([
            "carpool_opinion[satisfied]" => true,
            "carpool_opinion[opinion]" => "Le covoiturage était super bien passé"
        ]);
        $client->submit($opinionForm);

        // Re-fetch the crawler after submission to ensure DOM is up-to-date
        $crawler = $client->getCrawler();

        // Assert the element no longer exists
        $this->assertEquals(0, $crawler->filter('#carpool_opinion_submit')->count(), 'The element id exists');

    }*/
    /*
    public function testCarpoolOpinionWihoutOpinion()
    {
        $client = self::createPantherClient(['browser' => 'firefox']);
        $crawler = $client->request('GET', '/connexion');

        $client->waitFor('form'); // Wait for login form

        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'test4',
            'connexion[password]' => 'Test123?',
        ]);
        $client->submit($form);
        $crawler = $client->request('GET', '/userSpace');
        for ($i = 0; $i < 2; $i++) {
            $client->waitFor("#startBtn-18", 10);
            $crawler->filter("#startBtn-18")->click();
        }
        $crawler = $client->request('GET', '/connexion');

        $client->waitFor('form'); // Wait for login form

        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'boutoutben',
            'connexion[password]' => 'Boutout123!',
        ]);
        
        $client->submit($form);

        // Request the next page after login
        $crawler = $client->request('GET', '/userSpace');

        // Fill and submit the opinion form
        $opinionForm = $crawler->selectButton("Valider")->form([
            "carpool_opinion[satisfied]" => true,
        ]);

        $client->submit($opinionForm);

        // Re-fetch the crawler after submission to ensure DOM is up-to-date
        $crawler = $client->getCrawler();

        // Assert the element no longer exists
        $this->assertEquals(0, $crawler->filter('#carpool_opinion_submit')->count(), 'The element id exists');
    }*/
    public function testCarpoolOpinionWithInvalidOpinon()
    {
        $client = self::createPantherClient(['browser' => 'firefox']);
        /*
        $crawler = $client->request('GET', '/connexion');

        $client->waitFor('form'); // Wait for login form

        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'test4',
            'connexion[password]' => 'Test123?',
        ]);
        $client->submit($form);
        $crawler = $client->request('GET', '/userSpace');
        for ($i = 0; $i < 2; $i++) {
            $client->waitFor("#startBtn-44", 10);
            $crawler->filter("#startBtn-44")->click();
        }*/
        $crawler = $client->request('GET', '/connexion');

        $client->waitFor('form'); // Wait for login form

        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'boutoutben',
            'connexion[password]' => 'Boutout123!',
        ]);

        $client->submit($form);

        // Request the next page after login
        $crawler = $client->request('GET', '/userSpace');

        // Fill and submit the opinion form
        $opinionForm = $crawler->selectButton("Valider")->form([
            "carpool_opinion[satisfied]" => true,
            "carpool_opinion[opinion]" => "Le covoiturage était super bien passé;;;"
        ]);

        $client->submit($opinionForm);

        // Re-fetch the crawler after submission to ensure DOM is up-to-date
        $crawler = $client->getCrawler();

        // Assert the element no longer exists
        $this->assertSelectorTextContains('.form_error', 'Votre opinion doit comporter entre 10 et 500 caractères valides.'); 
    }

}