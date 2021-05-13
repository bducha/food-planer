<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MealController extends AbstractController
{
    #[Route('/api/meal', name: 'api_meal')]
    public function index(): Response
    {
        return $this->render('api/meal/index.html.twig', [
            'controller_name' => 'MealController',
        ]);
    }
}
