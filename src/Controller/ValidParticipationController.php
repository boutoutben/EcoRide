<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ValidParticipationController extends AbstractController
{
    #[Route('/validParticipation', name: 'app_valid_participation')]
    public function index(): Response
    {
        return $this->render('valid_participation/index.html.twig', [
            'controller_name' => 'ValidParticipationController',
        ]);
    }
}
