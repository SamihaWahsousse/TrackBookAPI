<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SpotbooksController extends AbstractController
{
    #[Route('/spotbooks', name: 'app_spotbooks_index')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/SpotbooksController.php',
        ]);
    }

    #[Route('/api/v1/spotbooks', name: 'app_spotbooks', methods: ["GET"])]
    public function showSpotbooks(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome First API!',
        ]);
    }
}
