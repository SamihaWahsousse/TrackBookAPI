<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;


class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    #[Route('/api/v1/users', name: 'users_get', methods: ["GET"])]
    public function getUsers(UserRepository $userRepository, SerializerInterface $serializer): Response
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        $json = $serializer->serialize($users, 'json', ['groups' => 'user:read']);
        $response = new Response($json, 200, ["Content-Type" => "application/json"]);
        return $response;
    }


    #[Route('/api/v1/users/{uuid}', name: 'users_get', methods: ["GET"])]
    public function getUsersByUuid($uuid, UserRepository $userRepository, SerializerInterface $serializer): Response
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(["uuid" => $uuid]);

        $json = $serializer->serialize($users, 'json', ['groups' => 'user:read']);
        $response = new Response($json, 200, ["Content-Type" => "application/json"]);
        return $response;
    }
}
