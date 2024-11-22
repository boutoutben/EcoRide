<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FullPresentationController extends AbstractController
{
    #[Route('/fullPresentation', name: 'app_full_presentation')]
    public function index(): Response
    {
        return $this->render('full_presentation/index.html.twig', [
            'controller_name' => 'FullPresentationController',
        ]);
    }
}
