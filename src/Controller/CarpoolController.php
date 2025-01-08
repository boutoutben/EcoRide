<?php

namespace App\Controller;

use App\Entity\Carpool;
use App\Entity\CarpoolParticipation;
use App\Entity\User;
use App\Form\FilterType;
use App\Form\SearchTravelType;
use App\Repository\CarpoolParticipationRepository;
use App\Repository\CarpoolRepository;
use App\Repository\OpinionRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarpoolController extends AbstractController
{
    private CarpoolRepository $carpoolRepository;
    private CarpoolParticipationRepository $carpoolParticipationRepository;
    private OpinionRepository $opinionRepository;
    public function __construct(CarpoolRepository $carpoolRepository,CarpoolParticipationRepository $carpoolParticipationRepository, OpinionRepository $opinionRepository)
    {
        $this->carpoolRepository = $carpoolRepository;
        $this->carpoolParticipationRepository = $carpoolParticipationRepository;
        $this->opinionRepository = $opinionRepository;
    }

    #[Route('/carpool', name: 'app_carpool')]
    public function index(Request $request): Response
    {
        $search = null;
        $allCarpool = [];
        $allSearch = [];
        $searchData = $request->query->all();
        $form = $this->createForm(SearchTravelType::class, null, [
            "default_start_place" => $searchData["startPlace"]??null,
            "default_end_place" => $searchData["endPlace"]??null
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()||$searchData != null ) {
            
            if($searchData!= null)
            {
                $data = $searchData;
            }
            else{
                $data = $form->getData();
            }
            $_SESSION["searchData"] = $data;
            
            // Build the query
            $allSearch = $this->searchCarpoolWithFilterOrNot($data,null);
            if ($this->getUser() != null) {
                $carpoolParticipation = $this->carpoolParticipationRepository->findBy(["user"=>$this->getUser()]);

                foreach ($carpoolParticipation as $carpoolParticipation) {
                    if($carpoolParticipation->getCarpool()->getStartPlace() == $data["startPlace"] && $carpoolParticipation->getCarpool()->getEndPlace() == $data['endPlace'] && $carpoolParticipation->getCarpool()->getStartDate()== $data["startDate"]){
                        $allCarpool[] = $carpoolParticipation->getCarpool();
                    }
                }
            }
        }
        
        $filterForm = $this->createForm(FilterType::class);
        $filterForm->handleRequest($request);
        if($filterForm->isSubmitted())
        {
            if($filterForm->isValid())
            {
              $filter = $filterForm->getData();
            $allSearch = $this->searchCarpoolWithFilterOrNot($_SESSION["searchData"],$filter);  
            }
            else{
                $allSearch = $this->searchCarpoolWithFilterOrNot($_SESSION["searchData"], null);
            }
        }
       
        return $this->render('carpool/index.html.twig', [
            "form" => $form->createView(),
            "search" => $allSearch,
            "carpoolParticipation" => $allCarpool,
            "filterForm" => $filterForm->createView(),
        ]);
        
    }

    public function searchCarpoolWithFilterOrNot($searchData,$filter)
    {
        $search = $this->carpoolRepository->searchCarpool($searchData, $filter);
        $allSearch = [];
        foreach ($search as $item) { // Avoid shadowing the $search variable
            $allSearch[] = $item;
        }
        return $allSearch;
    }
}
