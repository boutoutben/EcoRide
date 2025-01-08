<?php

namespace App\Controller;

use App\Repository\CarpoolRepository;
use App\Repository\OpinionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use MongoDB\Client;

class CarpoolDetailController extends AbstractController
{
    private CarpoolRepository $carpoolRepository;
    private OpinionRepository $opinionRepository;
    public function __construct(CarpoolRepository $carpoolRepository, OpinionRepository $opinionRepository)
    {
        $this->carpoolRepository = $carpoolRepository;
        $this->opinionRepository = $opinionRepository;
    }
    #[Route('/carpoolDetail', name: 'app_carpoop_detail')]
    public function index(): Response
    {
        $detail = $_GET["detail"];
        $carpool = $this->carpoolRepository->findOneBy(["id"=> $detail]);
        $formattedDate = $carpool->getStartDate()->format('l d F Y');
        $client = new Client($_ENV["MONGODB_URL"]);
);

        // Accéder à la collection "preferences" dans la base "ecoride"
        $collection = $client->ecoride->preferences;

        // Requête pour récupérer tous les documents de l'utilisateur
        $preferences = $collection->find(
            ['user' => $carpool->getUser()->getUsername(), "isValid"=> true],
        )->toArray();
        return $this->render('carpool_detail/index.html.twig', [
            'controller_name' => 'CarpoopDetailController',
            'formattedDate' => $formattedDate,
            "carpool" => $carpool,
            "preferences" => $preferences
        ]);
    }
}
