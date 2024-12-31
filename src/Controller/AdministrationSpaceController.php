<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CreateAccountType;
use App\Repository\CarpoolParticipationRepository;
use App\Repository\CarpoolRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AdministrationSpaceController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;
    private CarpoolRepository $carpoolRepository;
    private CarpoolParticipationRepository $carpoolParticipationRepository;
    private UserRepository $userRepository;

    // Injection de dépendances dans le constructeur
    public function __construct(UserPasswordHasherInterface $passwordHasher, CarpoolRepository $carpoolRepository, CarpoolParticipationRepository $carpoolParticipationRepository,UserRepository $userRepository)
    {
        $this->passwordHasher = $passwordHasher;
        $this->carpoolRepository = $carpoolRepository;
        $this->carpoolParticipationRepository = $carpoolParticipationRepository;
        $this->userRepository = $userRepository;
    }

    #[Route('/administrationSpace', name: 'app_administration_space')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher,EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(CreateAccountType::class);
        $form->handleRequest($request);
        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Set user data
            $user->setEmail($data["email"]);
            $user->setUsername($data["pseudo"]);
            $user->setRoles(['ROLE_EMPLOYEE']);

            // Check if passwords match
            if ($data["password"] !== $data["passwordEgain"]) {
                $form->addError(new FormError('Les deux mot de passe ne sont pas identique'));
            } else {
                // Hash password and save user
                $hashedPassword = $passwordHasher->hashPassword($user, $data["password"]);
                $user->setPassword($hashedPassword);
                $entityManager->persist($user);
                $entityManager->flush();

                // Redirect after successful registration
                return $this->redirectToRoute('app_administration_space', [], Response::HTTP_SEE_OTHER);
            }
        }
        $nbTotalCreditPlatform = $this->carpoolParticipationRepository->nbTotalCreditPlatform(2);
        $allUser = $this->userRepository->getUserAndEmployee();

        return $this->render('administration_space/index.html.twig', [
            'controller_name' => 'AdministrationSpaceController',
            "form" => $form->createView(),
            "nbTotalCreditPlatform" => $nbTotalCreditPlatform,
            "allUser" => $allUser
        ]);
    }
    #[Route('/carpoolAvg', name:'app_carpool_avg', methods: ['POST'])]
    public function carpoolAvg(): JsonResponse
    {
        // Decode the JSON input
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        // Validate JSON decoding
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            return new JsonResponse(['error' => 'Invalid JSON input'], 400);
        }


        $date = new DateTime($data["currentDate"]);
        $date->modify('this week monday'); // Start from the Monday of the current week

        // Calculate carpool counts for each day of the week
        $dayCounts = [];
        for ($i = 0; $i < 7; $i++) {
            $dayName = $date->format('l'); // Get day name (e.g., Monday, Tuesday)
            $dayCounts[$dayName] = $this->carpoolRepository->countDate(clone $date);
            $date->modify('+1 day');
        }

        // Return the counts in JSON format
        return new JsonResponse([
            'dayCounts' => $dayCounts,
        ]);
    }

    #[Route('/nbCreditAvg', name:'app_nb_credit_avg', methods: ['POST'])]
    public function nbCreditAvg(): JsonResponse
    {
        // Decode the JSON input
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        // Validate JSON decoding
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            return new JsonResponse(['error' => 'Invalid JSON input'], 400);
        }


        $date = new DateTime($data["currentDate"]);
        $date->modify('this week monday'); // Start from the Monday of the current week

        // Calculate carpool counts for each day of the week
        $dayCounts = [];
        for ($i = 0; $i < 7; $i++) {
            $dayName = $date->format('l'); // Get day name (e.g., Monday, Tuesday)
            $dayCounts[$dayName] = $this->carpoolParticipationRepository->countCreditPlatform(clone $date, 2);
            $date->modify('+1 day');
        }

        // Return the counts in JSON format
        return new JsonResponse([
            'dayCounts' => $dayCounts,
        ]);
    }

    #[Route('/suspend', name:'app_suspend',methods: ["POST"])]
    public function suspend(EntityManagerInterface $em): JsonResponse
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        $id = $data["id"];
        $condition = $data["condition"];
        $user = $this->userRepository->findOneBy(["id"=>$id]);
        $user->setSuspend($condition);
        $em->persist($user);
        $em->flush();
        return new JsonResponse($id);
    }
}
