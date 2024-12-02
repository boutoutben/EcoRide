<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserRememberController extends AbstractController
{
    #[Route('/api/me', name:"app_user_api_me")]
    #[IsGranted("IS_AUTHENTICATED_REMEMBERED")]
    public function apiMe(): Response
    {
        return $this->json($this->getUser(), 200, [], [
            'groups' => ['user:read']
        ]);
    }
}
