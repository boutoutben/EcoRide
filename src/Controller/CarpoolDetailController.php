<?php

namespace App\Controller;

use App\Repository\CarpoolRepository;
use App\Repository\OpinionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
        $mark = $this->opinionRepository->getAVGMark($carpool->getUser());
        $formattedDate = $carpool->getStartDate()->format('l d F Y');
        return $this->render('carpool_detail/index.html.twig', [
            'controller_name' => 'CarpoopDetailController',
            'formattedDate' => $formattedDate,
            "carpool" => $carpool,
            "mark" => $mark
        ]);
    }
}
