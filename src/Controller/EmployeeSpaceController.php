<?php

namespace App\Controller;

use App\Repository\OpinionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmployeeSpaceController extends AbstractController
{
    public OpinionRepository $opinionRepository;
    public function __construct(OpinionRepository $opinionRepository) {
        $this->opinionRepository = $opinionRepository;
    }
    #[Route('/employeeSpace', name: 'app_employee_space')]
    public function index(): Response
    {
        $opinions = $this->opinionRepository->findBy(["isValid"=>false]);
        return $this->render('employee_space/index.html.twig', [
            'controller_name' => 'EmployeeSpaceController',
            "opinions" => $opinions
        ]);
    }

    #[Route("/validOpinion", name: "app_valid_opinion")]
    public function validOpinion(EntityManagerInterface $em): Response
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'];
        $valid = $data["value"];
        $opinion = $this->opinionRepository->findOneBy(["id"=>$id]);
        if($valid){
            $opinion->setValid(true);
        }
        else{
            $opinion->setOpinion(null);
        }
        $em->persist($opinion);
        $em->flush();
        return new JsonResponse("Success");
    }
}
