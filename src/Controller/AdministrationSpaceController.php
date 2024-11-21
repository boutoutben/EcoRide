<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdministrationSpaceController extends AbstractController
{
    #[Route('/administrationSpace', name: 'app_administration_space')]
    public function index(): Response
    {
        return $this->render('administration_space/index.html.twig', [
            'controller_name' => 'AdministrationSpaceController',
        ]);
    }
}
