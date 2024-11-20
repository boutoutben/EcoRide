<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreateAccountController extends AbstractController
{
    #[Route('/createAccount', name: 'app_create_account')]
    public function index(): Response
    {
        return $this->render('create_account/index.html.twig', [
            'controller_name' => 'CreateAccountController',
        ]);
    }
}
