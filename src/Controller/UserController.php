<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

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

    #[Route('/api/v1/users', name: 'get_users', methods: ["GET"])]
    public function getUsers(UserRepository $userRepository, SerializerInterface $serializer): Response
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        $json = $serializer->serialize($users, 'json', ['groups' => 'user:read']);
        $response = new Response($json, 200, ["Content-Type" => "application/json"]);
        return $response;
    }


    #[Route('/api/v1/users/login', name: 'getUserBy_uuid', methods: ["POST"])]
    public function getUsersByUuid(UserRepository $userRepository, SerializerInterface $serializer, Request $request): Response
    {
        // $users = $this->getDoctrine()
        //     ->getRepository(User::class)
        //     ->findOneBy(["id" => $id]);
        // $json = $serializer->serialize($users, 'json', ['groups' => 'user:read']);
        // $response = new Response($json, 200, ["Content-Type" => "application/json"]);
        // return $response;



        //first method without the symfonyRequest-bundle
        // $userId = json_decode($request->getContent(), true);
        // $uuid = $userId['uuid'];

        //Second method with the symfonyRequest-bundle
        $uuid = $request->get("uuid");
        try {
            $users = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(["uuid" => $uuid]);
            if (!$users) {
                return $this->json(["error" => " User not found"], 201);
            }

            $json = $serializer->serialize($users, 'json', ['groups' => 'user:read']);
            $response = new Response($json, 200, ["Content-Type" => "application/json"]);
            return $response;
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'staut' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
