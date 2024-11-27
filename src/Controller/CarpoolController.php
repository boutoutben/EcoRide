<?php

namespace App\Controller;

use App\Entity\Carpool;
use App\Form\SearchTravelType;
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
    public function __construct(CarpoolRepository $carpoolRepository) 
    {
        $this->carpoolRepository = $carpoolRepository;
    }

    #[Route('/carpool', name: 'app_carpool')]
    public function index(Request $request): Response
    {
        $search = "empty";
        $carpool = new Carpool();
        $form = $this->createForm(SearchTravelType::class, $carpool);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Debugging submitted request data
            if($form->isValid()){
                // Get form data
                $startPlace = $_POST["startPlace"];
                $endPlace = $_POST["endPlace"];
                $startDate = $_POST["startDate"];


                // Build the query
                $queryBuilder = $this->carpoolRepository->createQueryBuilder('c')
                    ->select('c', 'u') // Select fields from both Carpool (c) and User (u)
                    ->join('c.user', 'u') // Adjust this to your actual relationship
                    ->where('c.startPlace = :start_place')
                    ->andWhere('c.endPlace = :end_place')
                    ->andWhere('c.startDate >= :startDate') // Ensure you're using the correct date comparison
                    ->setParameters(new ArrayCollection([
                        new Parameter('start_place', $startPlace),
                        new Parameter('end_place', $endPlace),
                        new Parameter("startDate", $startDate)
                    ]));

                $search = $queryBuilder->getQuery()->getResult();
            } else {
                // Handle invalid form, check for errors
                dump($form->getErrors(true));
            }
            
        } 
        return $this->render('carpool/index.html.twig', [
            'controller_name' => 'CarpoolController',
            "form" => $form->createView(),
            "search" => $search
        ]);
        
    }
}
