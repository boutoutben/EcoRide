<?php

namespace App\Controller;

use App\Entity\Carpool;
use App\Entity\CarpoolParticipation;
use App\Form\SearchTravelType;
use App\Repository\CarpoolParticipationRepository;
use App\Repository\CarpoolRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarpoolController extends AbstractController
{
    private CarpoolRepository $carpoolRepository;
    private CarpoolParticipationRepository $carpoolParticipationRepository;
    public function __construct(CarpoolRepository $carpoolRepository,CarpoolParticipationRepository $carpoolParticipationRepository) 
    {
        $this->carpoolRepository = $carpoolRepository;
        $this->carpoolParticipationRepository = $carpoolParticipationRepository;
    }

    #[Route('/carpool', name: 'app_carpool')]
    public function index(Request $request): Response
    {
        $search = null;
        $allCarpool = [];
        $form = $this->createForm(SearchTravelType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $startPlace = $data["startPlace"];
            $endPlace = $data["endPlace"];
            $startDate = $data["startDate"];
                
            // Build the query
            $queryBuilder = $this->carpoolRepository->createQueryBuilder('c')
                ->select('c', 'u') // Select fields from both Carpool (c) and User (u)
                ->join('c.user', 'u') // Adjust this to your actual relationship
                ->where('c.startPlace = :start_place')
                ->andWhere('c.endPlace = :end_place')
                ->andWhere("c.startDate >= :startDate")
                ->andWhere("c.isFinish = false")
                ->andWhere("c.placeLeft > 0")
                ->setParameters(new ArrayCollection([
                    new Parameter('start_place', $startPlace),
                    new Parameter('end_place', $endPlace),
                    new Parameter("startDate", $startDate)
                ]));
            $search = $queryBuilder->getQuery()->getResult();
            if ($this->getUser() != null) {
                $carpoolParticipation = $this->carpoolParticipationRepository->findBy(["user"=>$this->getUser()]);

                foreach ($carpoolParticipation as $carpoolParticipation) {
                    if($carpoolParticipation->getCarpool()->getStartPlace() == $startPlace && $carpoolParticipation->getCarpool()->getEndPlace() == $endPlace && $carpoolParticipation->getCarpool()->getStartDate()){
                        $allCarpool[] = $carpoolParticipation->getCarpool();
                    }
                }
            }
        }
        return $this->render('carpool/index.html.twig', [
            'controller_name' => 'CarpoolController',
            "form" => $form->createView(),
            "search" => $search,
            "carpoolParticipation" => $allCarpool
        ]);
        
    }
}
