<?php

namespace App\Controller;

use App\Repository\OpinionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OpinionController extends AbstractController
{
    private UserRepository $userRepository;
    private OpinionRepository $opinionRepository;
    public function __construct(UserRepository $userRepository, OpinionRepository $opinionRepository) {
        $this->userRepository = $userRepository;
        $this->opinionRepository = $opinionRepository;
    }
    #[Route('/opinion', name: 'app_opinion')]
    public function index(): Response
    {
        $id = $_GET["id"];
        $user = $this->userRepository->findOneBy(['id'=>$id]);
        $opinion = $this->opinionRepository->findBy(["driver" => $user,"isValid"=> true]);
        return $this->render('opinion/index.html.twig', [
            'controller_name' => 'OpinionController',
            "user" => $user,
            "opinion" => $opinion
        ]);
    }
}
