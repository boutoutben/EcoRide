<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserSpaceController extends AbstractController
{
    #[Route('/userSpace', name: 'app_user_space')]
    public function index(): Response
    {
        return $this->render('user_space/index.html.twig', [
            'controller_name' => 'UserSpaceController',
        ]);
    }
}
