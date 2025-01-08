<?php

namespace App\Controller;

use App\Form\SearchTravelType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(SearchTravelType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            $queryParams = http_build_query($data);

            // Redirect to the carpool page with the query parameters
            return new RedirectResponse("/carpool?" . $queryParams);
        }
        return $this->render("Home/home.html.twig",[
            "form" => $form->createView() 
        ]);
    }
}
