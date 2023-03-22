<?php

namespace App\Controller;

use App\Entity\Spotbooks;
use App\Repository\SpotbooksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SpotbooksController extends AbstractController
{
    #[Route('/spotbooks', name: 'spotbooks_get', methods: ["GET"])]
    public function getSpotBooks(SpotbooksRepository $spotbooksRepository, SerializerInterface $serializer): Response
    {
        $spotBooks = $this->getDoctrine()
            ->getRepository(Spotbooks::class)
            ->findAll();

        $json = $serializer->serialize($spotBooks, 'json', ['groups' => 'spotBooks:read']);
        $response = new Response($json, 200, [" Content-Type " => "application/json"]);
        return $response;
    }


    #[Route('/spotbooks', name: 'spotbooks_post', methods: ["POST"])]

    public function addSpotBooks(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        try {
            $jsonRecieved = $request->getContent();
            $spotBooks = $serializer->deserialize($jsonRecieved, Spotbooks::class, 'json');

            //Manage JSON Content Format error befor the persist ans flush in the DB
            $errors = $validator->validate($spotBooks);
            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }

            //add to DataBase
            $em->persist($spotBooks);
            $em->flush();
            return $this->json($spotBooks, 201, [], ['groups' => 'spotBooks:read']);
        }
        //manage the syntax of a jsonRequest (if missing { or ,)an exception message is generated
        catch (NotEncodableValueException $e) {
            return $this->json([
                'staut' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
