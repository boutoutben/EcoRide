<?php

namespace App\Controller;

use App\Entity\CarpoolParticipation;
use App\Repository\CarpoolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class ValidParticipationController extends AbstractController
{
    private CarpoolRepository $carpoolRepository;
    public function __construct(CarpoolRepository $carpoolRepository)
    {
        $this->carpoolRepository = $carpoolRepository;
    }

    #[Route('/validParticipation', name: 'app_valid_participation')]
    public function index(Request $request): Response
    {
        if(!$this->isGranted("IS_AUTHENTICATED_REMEMBERED"))
        {
            $this->addFlash('error', 'Pour participer à un covoiturage vous devez être connecté');
            return new RedirectResponse("connexion");
        }
        $detail = $_GET["detail"];
        if ($this->getUser()->getUserType() == "Conducteur") {
            $this->addFlash('error', 'Seul les passagers peut participer à un covoiturage, mais vous pouvais être les deux en même temps en cochant la case');
            return new RedirectResponse("carpoolDetail?detail=${detail}");
        }
        $carpool = $this->carpoolRepository->findOneBy(["id" => $detail]);
        $user = $this->getUser();
        return $this->render('valid_participation/index.html.twig', [
            'controller_name' => 'ValidParticipationController',
            "carpool" => $carpool,
            "user" => $user
        ]);
    }

    #[Route('/validation', name: 'app_validation')]
    public function validation(EntityManagerInterface $entityManager):Response
    {
        $detail = $_GET["detail"];
        $carpool = $this->carpoolRepository->findOneBy(["id" => $detail]);
        $carpoolParticipation = new CarpoolParticipation();
        $user = $this->getUser();
        if($user->getNbCredit() >= $carpool->getPrice())
        {
            $user->setNbCredit($user->getNbCredit() - $carpool->getPrice());
            $carpool->setPlaceLeft($carpool->getPlaceLeft()-1);
            $carpoolParticipation->setCarpool($carpool)
                                 ->setUser($this->getUser());
            $entityManager->persist($carpool);
            $entityManager->persist($carpoolParticipation);
            $entityManager->persist($user);
            $entityManager->flush();
        }
        else{
            $this->addFlash("error","Vous n'avez pas assez de crédit.");
        }
        $url = $this->generateUrl('app_carpoop_detail') . '?detail=' . $carpool->getId();
        return new RedirectResponse($url);
    }
}
