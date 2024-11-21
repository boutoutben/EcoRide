<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmployeeSpaceController extends AbstractController
{
    #[Route('/employeeSpace', name: 'app_employee_space')]
    public function index(): Response
    {
        return $this->render('employee_space/index.html.twig', [
            'controller_name' => 'EmployeeSpaceController',
        ]);
    }
}
