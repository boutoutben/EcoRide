<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfileType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;
use UnexpectedValueException;

class UserSpaceController extends AbstractController
{
    private UserRepository $userRepository;
    private RouterInterface $route;
    public function __construct(UserRepository $userRepository, private UserPasswordHasherInterface $passwordHasher, RouterInterface $route) {
        $this->userRepository = $userRepository;
        $this->route = $route;
    }

    public function verifyPassword($user, $plainPassword)
    {
        return $this->passwordHasher->isPasswordValid($user, $plainPassword);
    }
    #[Route('/userSpace', name: 'app_user_space')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $userInfo = $this->getUser();
        $user = $this->userRepository->findOneBy(['id' =>$userInfo->getId()]);
        if (array_key_exists('file', $_FILES)){
            dd("cc");
           if ($_FILES["file"]["name"] != "") {
                $test = explode(".", $_FILES["file"]["name"]);
                dd($test);
                $extension = end($test);
                $name = rand(100, 999) . '.' . $extension;
                $location = "/public/img/" . $name;
                move_uploaded_file($_FILES["file"]["tmp_name"],$location);
            }
        }
        
        $form = $this->createForm(UserProfileType::class, null, [
            'default_choice' => $userInfo->getUserType()
        ]);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $data = $form->getData();
            $user->setUserType($data["userType"]);
            $user->setName($data["name"]);
            $user->setSurname($data["surname"]);
            $user->setEmail($data['email']);
            if(isset($data["phone"]))
            {
                $user->setPhone($data["phone"]);
            }
            if(isset($data["password"]) && isset($data["newPassword"]))
            {
                
                if($this->verifyPassword($user,$data["password"]))
                {
                    $user->setPlainPassword($data["newPassword"]);
                    $user->setPassword($passwordHasher->hashPassword($user,$user->getPlainPassword()));
                }
                else{
                    $form->addError(new FormError("Le mot de passe saisie n'est pas correct"));
                }
            }
            elseif(isset($data["password"]) || isset($data["newPassword"]))
            {
                $form->addError(new FormError("Si vous souhaitez mettre à jour le mot de passe, il faut remplir les deux champs "));
            }
            $entityManager->persist($user);
            $entityManager->flush();
              
        }
        return $this->render('user_space/index.html.twig', [
            'controller_name' => 'UserSpaceController',
            "form" =>$form->createView(),
            "userInfo" => $userInfo,
            "nbType" => 1
        ]);
    }
    #[Route('/userPictureUpload', name: 'app_user_upload_picture', methods:["POST"])]
    public function upload(EntityManagerInterface $entityManager, Request $request): Response
    {
        if (!isset($_FILES['file'])) {
            throw new \Exception('No file uploaded.');
        }
        $userInfo = $this->getUser();
        $user = $this->userRepository->findOneBy(['id' => $userInfo->getId()]);
        if ($_FILES["file"]["name"] != "") {
            $test = explode(".", $_FILES["file"]["name"]);
            $extension = end($test);
            $name = $test[0] . '.' . $extension;
            $uploadDir = $this->getParameter('kernel.project_dir') . '/public/img';
            $location = $uploadDir . '/' . $name;
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $location)) {
                // Optionally, return the file path for use in the application
                $relativePath = '/img/' . $name; // Path relative to the public directory
                // Store $relativePath in the database or use it as needed
            } else {
                throw new UnexpectedValueException("Le fichier n'a pas pu être déplacer dans le répertoire");
            }
            $user->setImg($name);
            $entityManager->persist($user);
            $entityManager->flush();
            return new JsonResponse([ 'file' => $name, "path" => $relativePath]);
        }

        return new JsonResponse(['message' => 'No file uploaded!'], 400);
    }
}
