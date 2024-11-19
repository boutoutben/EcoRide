<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarpoolDetailController extends AbstractController
{
    #[Route('/carpoolDetail', name: 'app_carpoop_detail')]
    public function index(): Response
    {
        return $this->render('carpool_detail/index.html.twig', [
            'controller_name' => 'CarpoopDetailController',
        ]);
    }
}
