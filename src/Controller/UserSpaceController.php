<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\User;
use App\Form\AddPreferenceType;
use App\Form\EditCarType;
use App\Form\NewCarType;
use App\Form\UserPreferencesType;
use App\Form\UserProfileType;
use App\Repository\CarRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
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
    private CarRepository $carRepository;
    private RouterInterface $route;
    private $formFactory;
    public function __construct(UserRepository $userRepository, private UserPasswordHasherInterface $passwordHasher, RouterInterface $route,CarRepository $carRepository, FormFactoryInterface $formFactory) {
        $this->userRepository = $userRepository;
        $this->carRepository = $carRepository;
        $this->route = $route;
        $this->formFactory = $formFactory;
    }

    public function verifyPassword($user, $plainPassword)
    {
        return $this->passwordHasher->isPasswordValid($user, $plainPassword);
    }
    #[Route('/userSpace', name: 'app_user_space')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $nbCar = 1;
        $userInfo = $this->getUser();
        $user = $this->userRepository->findOneBy(['id' =>$userInfo->getId()]);
        if (array_key_exists('file', $_FILES)){
           if ($_FILES["file"]["name"] != "") {
                $test = explode(".", $_FILES["file"]["name"]);
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
        $userCar = $this->carRepository->findBy(["user"=> $this->getUser()->getId()]);
        $nbTotalCar = count($userCar);
        $newCarform = $this->createForm(NewCarType::class, null, [
            "action" => "/userSpace#plus-btn"
        ]);
        $car = new Car();
        $newCarform->handleRequest($request);
        if($newCarform->isSubmitted()&&$newCarform->isValid()){
            $data = $newCarform->getData();
            $car->setLicensePlate($data["licensePlate"])
                ->setFirstRegistration($data["firstImmatriculation"])
                ->setMark($data["mark"])
                ->setModel($data["model"])
                ->setColor($data["color"])
                ->setEnergie($data["energie"])
                ->setNbPassenger($data["nbPassenger"])
                ->setUser($this->getUser());
            $entityManager->persist($car);
            $entityManager->flush();
            return new RedirectResponse(
                $this->route->generate("app_user_space")
            );
        }
        $carAndCarEditForm = []; // Initialize the array to hold car forms

        // Loop over each car and create a form for each
        foreach ($userCar as $index => $carEdit) {
            $carAndCarEditForm[$index][0] = $carEdit; // Store the car object
            $formEdit = $this->createForm(EditCarType::class, $carEdit, [
                "default_choice" => $carEdit->getMark(),
                "default_date" => $carEdit->getFirstRegistration(),
                "csrf_token_id" => "edit_car" . $index, // Unique CSRF token for each form
                "default_name" => "edit_car" . $index
            ]);
            //$formEdit->createName
            // Handle the form submission
            $formEdit->handleRequest($request);

            // If form is submitted and valid, save the car
            if ($formEdit->isSubmitted() && $formEdit->isValid()) {
                // Ensure you're persisting the exact same $carEdit that was edited
                $entityManager->persist($carEdit);
                $entityManager->flush();

                // Redirect after saving
                return $this->redirectToRoute('app_user_space');
            }

            // Store the form view for the car
            $carAndCarEditForm[$index][1] = $formEdit->createView();
        }
        $preferences = $user->getPreference();

        // Extract pre-selected preferences where the second value is true
        $selectedPreferences = [];
        foreach ($preferences as $preference) {
            if ($preference[1] === true) {
                $selectedPreferences[] = $preference[0]; // Collect names of preferences with true status
            }
        }

        $preferenceForm = $this->createForm(UserPreferencesType::class, null, [
            "data_preference" => $this->getUser(),
            'selectedPreferences' => $selectedPreferences,
        ]);
        $preferenceForm->handleRequest($request);
        $preferences = $this->getUser()->getPreference();
        if($preferenceForm->isSubmitted()&&$preferenceForm->isValid()){
            $data = $preferenceForm->getData();
            foreach ($preferences as &$storedPreference) {
                $storedPreference[1] = false;
            }
            if (!empty($data['choicePreferences'])) {
                foreach ($data['choicePreferences'] as $submittedPreference) {
                    foreach ($preferences as &$storedPreference) {
                        if ($storedPreference[0] === $submittedPreference) {
                            $storedPreference[1] = true;
                        }
                    }
                }
            }
            $user->setPreference($preferences);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_space');
        }
        $addPreferenceForm = $this->createForm(AddPreferenceType::class);
        $user = $this->getUser();
        $addPreferenceForm->handleRequest($request);
        if($addPreferenceForm->isSubmitted()&&$addPreferenceForm->isValid()){
            $data = $addPreferenceForm->getData();
            if(isset($data["newPreference"])){
                $user->addPreference($data["newPreference"]);
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('app_user_space');
            }
            
        }
        $nonce = base64_encode(random_bytes(16));

        return $this->render('user_space/index.html.twig', [
            'controller_name' => 'UserSpaceController',
            "form" =>$form->createView(),
            "nbTotalCar" => $nbTotalCar,
            "newCarForm" => $newCarform->createView(),
            "userInfo" => $userInfo,
            "nbType" => 1,
            "nbCar" => $nbCar,
            "carAndCarEditForm" => $carAndCarEditForm,
            "preferenceForm" => $preferenceForm->createView(),
            "addPreferenceForm" => $addPreferenceForm->createView(),
            'csp_nonce' => $nonce,
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
