<?php

namespace App\Controller;

use App\Form\SearchTravelType;
use App\Repository\CarpoolRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarpoolController extends AbstractController
{
    private CarpoolRepository $carpoolRepository;
    public function __construct(CarpoolRepository $carpoolRepository) 
    {
        $this->carpoolRepository = $carpoolRepository;
    }

    #[Route('/carpool', name: 'app_carpool')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $start_place = $_POST["start_place"]??"";
        $end_place = $_POST["end_place"] ?? "";
        $date = $_POST["date"] ?? "";
        $search = null;
        $form = $this->createForm(SearchTravelType::class, null,[
            "method" => "post"
        ]);
        
        if($end_place!="")
        {
            $search = $this->carpoolRepository->createQueryBuilder('c')
            ->select('c', 'u') // Select fields from both Carpool (c) and User (u)
            ->join('c.user', 'u') // Join the related user table (adjust 'c.user' to match your relationship)
            ->where('c.startPlace = :start_place')
            ->andWhere('c.endPlace = :end_place')
            ->andWhere('c.startDate >= :date')
            ->setParameters(new ArrayCollection([
                new Parameter('start_place', $start_place),
                new Parameter('end_place', $end_place),
                new Parameter('date', $date),
            ]))
            ->getQuery()
            ->getResult();
            
        }
        
        return $this->render('carpool/index.html.twig', [
            'controller_name' => 'CarpoolController',
            "form" => $form,
            "search" => $search
        ]);
    }

}
