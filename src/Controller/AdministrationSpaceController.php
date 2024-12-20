<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CreateAccountType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AdministrationSpaceController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;

    // Injection de dépendances dans le constructeur
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
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
        return $this->render('administration_space/index.html.twig', [
            'controller_name' => 'AdministrationSpaceController',
            "form" => $form->createView()
        ]);
    }
}
