<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarpoolController extends AbstractController
{
    #[Route('/carpool', name: 'app_carpool')]
    public function index(): Response
    {
        return $this->render('carpool/index.html.twig', [
            'controller_name' => 'CarpoolController',
        ]);
    }
}
