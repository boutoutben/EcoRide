<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ConnexionType;
use App\Form\CreateAccountType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ConnexionController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;

    // Injection de dépendances dans le constructeur
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/connexion', name: 'app_connexion')]
    public function login(AuthenticationUtils $authenticationUtils,Request $request): Response
    {
        $form = $this->createForm(ConnexionType::class);
        $form->handleRequest($request);

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
            "form" => $form->createView(),
            "error" => $error,
            "lastUsername" => $lastUsername
        ]);
    }

    #[Route('/signIn', name: 'app_sign_in')]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $user = new User();
        $form = $this->createForm(CreateAccountType::class);
        $form->handleRequest($request);
        // Check if the form is submitted and valid
        if ($form->isSubmitted()&& $form->isValid()) {
            $data = $form->getData();

            // Set user data
            $user->setEmail($data["email"]);
            $user->setUsername($data["pseudo"]);
            $user->setNbCredit(20);
            $user->setRoles(['ROLE_USER']);
            $user->setUserType("Passager");

            // Check if passwords match
            if ($data["password"] !== $data["passwordEgain"]) {
                $form->addError(new FormError('The passwords do not match.'));
                
            } else {
                // Hash password and save user
                $hashedPassword = $passwordHasher->hashPassword($user, $data["password"]);
                $user->setPassword($hashedPassword);
                $entityManager->persist($user);
                $entityManager->flush();

                // Redirect after successful registration
                return $this->redirectToRoute('app_connexion', [], Response::HTTP_SEE_OTHER);
            }
        }

        // Always return a response (render the form)
        return $this->render('create_account/index.html.twig', [
            "form" => $form->createView(),
        ]);
    }
    /*
    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager)
    {
        /*$form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
    */
}
