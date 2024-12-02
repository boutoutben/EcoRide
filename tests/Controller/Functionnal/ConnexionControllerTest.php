<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ConnexionControllerTest extends WebTestCase
{
    //Signin
    public function testSignInPage()
    {
        $client = static::createClient();
        $client->request("GET", "/signIn");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
/*
    public function testSignInFormSubmissionWithValidData()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/signIn');

        $form = $crawler->selectButton('Créer un compte')->form([
            'create_account[email]' => 'test@gmail.com',
            'create_account[pseudo]' => 'test4',
            'create_account[password]' => 'Test123!',
            'create_account[passwordEgain]' => 'Test123!'
        ]);

        $client->submit($form);

        // Follow the redirection
        $client->followRedirect();

        // Assert the status code after redirection
        $this->assertResponseIsSuccessful();
    }
    */
    public function testSignInFormSubmissionWithInValidEmail()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/signIn');
        $form = $crawler->selectButton('Créer un compte')->form([
            'create_account[email]' => 'testgmail.com',
            'create_account[pseudo]' => 'test',
            'create_account[password]' => 'Test123!',
            "create_account[passwordEgain]" => "Test123!"
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $client->getResponse()->getContent();
        $this->assertSelectorTextContains('.form_error', 'Ce champ donc contenir un email');
    }
    public function testSignInFormSubmissionWithInValidPseudo()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/signIn');
        $form = $crawler->selectButton('Créer un compte')->form([
            'create_account[email]' => 'test@gmail.com',
            'create_account[pseudo]' => 'test;;',
            'create_account[password]' => 'Test123!',
            "create_account[passwordEgain]" => "Test123!"
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('.form_error', "Le pseudo n'est pas conforme");
    }

    public function testSignInFormSubmissionWithInValidPassword()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/signIn');
        $form = $crawler->selectButton('Créer un compte')->form([
            'create_account[email]' => 'test@gmail.com',
            'create_account[pseudo]' => 'test',
            'create_account[password]' => 'Test123456',
            "create_account[passwordEgain]" => "Test123!"
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('.form_error', "Le mot de passe n'est pas assez fort");
    }

    public function testSignInFormSubmissionWithInValidCheckPassword()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/signIn');
        $csrfToken = $crawler->filter('input[name="create_account[_token]"]')->attr('value');
        $form = $crawler->selectButton('Créer un compte')->form([
            'create_account[email]' => 'test@gmail.com',
            'create_account[pseudo]' => 'test123',
            'create_account[password]' => 'Test123!',
            "create_account[passwordEgain]" => "Test123453!",
            'create_account[_token]' => $csrfToken,
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('.form_error', "The passwords do not match.");
    }
    public function testSignInFormSubmissionWithInValidMax()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/signIn');
        $form = $crawler->selectButton('Créer un compte')->form([
            'create_account[email]' => 'redfiuazeriopfuazeproifuzeporiuzeoipruapzoieurpazeoiurpoaizeuroaizpeurazieopruapoiezuraipoezruzaoipe@gmail.com',
            'create_account[pseudo]' => 'test123',
            'create_account[password]' => 'Test123!',
            "create_account[passwordEgain]" => "Test123453!",
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('.form_error', "Il y a trop de caractère, il en faut maximum 50");
    }
    public function testSignInFormSubmissionWithInValidEmpty()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/signIn');
        $form = $crawler->selectButton('Créer un compte')->form([
            'create_account[email]' => '',
            'create_account[pseudo]' => '',
            'create_account[password]' => '',
            "create_account[passwordEgain]" => "",
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('.form_error', "Le champ ne doit pas être vide");
    }

    //login
    public function testConnexionFormSubmissionWithValidData()
    {
        $client = static::createClient();

        // Fetch the login form page
        $crawler = $client->request('GET', '/connexion');
        $this->assertResponseIsSuccessful(); // Ensure the form page loads successfully

        // Select the form and fill it
        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'test',
            'connexion[password]' => 'Test123!',
        ]);

        // Submit the form
        $client->submit($form);

        // Follow the redirection after submission
        $this->assertResponseRedirects('/'); // Adjust the redirection path
        $client->followRedirect();

        // Assert the status code after redirection
        $this->assertResponseIsSuccessful();
    }
    public function testConnexionFormSubmissionWithInvaliUsername()
    {
        $client = static::createClient();

        // Fetch the login form page
        $crawler = $client->request('GET', '/connexion');
        $this->assertResponseIsSuccessful(); // Ensure the form page loads successfully

        // Select the form and fill it
        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'tes',
            'connexion[password]' => 'Test123!',
        ]);

        // Submit the form
        $client->submit($form);

        // Follow the redirection after submission
        $this->assertResponseRedirects('/connexion'); // Adjust the redirection path
        $client->followRedirect();
        // Assert the status code after redirection
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.form_error', "Le pseudo n'est pas enrigistrer");
    }
    public function testConnexionFormSubmissionWithInvalidPassword()
    {
        $client = static::createClient();

        // Fetch the login form page
        $crawler = $client->request('GET', '/connexion');
        $this->assertResponseIsSuccessful(); // Ensure the form page loads successfully

        // Select the form and fill it
        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'test',
            'connexion[password]' => 'Test123?',
        ]);

        // Submit the form
        $client->submit($form);
        // Follow the redirection after submission
        $this->assertResponseRedirects('/connexion'); // Adjust the redirection 
        $client->followRedirect();
        // Assert the status code after redirection
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.form_error', "Mot de passe invalide");
    }
    public function testConnexionFormSubmissionWithInvalidRegexUsername()
    {
        $client = static::createClient();

        // Fetch the login form page
        $crawler = $client->request('GET', '/connexion');
        $this->assertResponseIsSuccessful(); // Ensure the form page loads successfully

        // Select the form and fill it
        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'test;;',
            'connexion[password]' => 'Test123!',
        ]);

        // Submit the form
        $client->submit($form);
        // Follow the redirection after submission
        $this->assertResponseRedirects('/connexion'); // Adjust the redirection 
        $client->followRedirect();
        // Assert the status code after redirection
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.form_error', "Le nom d'utilisateur doit contenir en 3 et 50 et ne peux contenir uniquement certain caractère spécial (. , ! ? () : -)");
    }

    public function testConnexionFormSubmissionWithInvalidRegexPassword()
    {
        $client = static::createClient();

        // Fetch the login form page
        $crawler = $client->request('GET', '/connexion');
        $this->assertResponseIsSuccessful(); // Ensure the form page loads successfully

        // Select the form and fill it
        $form = $crawler->selectButton('Connexion')->form([
            'connexion[pseudo]' => 'test',
            'connexion[password]' => 'Test123',
        ]);

        // Submit the form
        $client->submit($form);
        // Follow the redirection after submission
        $this->assertResponseRedirects('/connexion'); // Adjust the redirection 
        $client->followRedirect();
        // Assert the status code after redirection
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.form_error', "Le mot de passe doit contenir en 3 et 50 caractère and être constitué au moins d'une majuscule, d'une minuscule, d'un chiffre et d'un caractère spécial");
    } 
}
