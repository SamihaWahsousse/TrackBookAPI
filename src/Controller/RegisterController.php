<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        //instanciate the User class
        $user = new User();

        //instanciate the register form with creatForm method
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        $entityManager = $doctrine->getManager();

        if ($form->isSubmitted() && $form->isValid()) {

            // $form->getData() holds the submitted values
            $user = $form->getData();
            $plaintextPassword = $user->getPassword();

            // hash the password (based on the security.yaml config for the $user class)
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hashedPassword);

            //send the data to database
            $entityManager->persist($user);
            $entityManager->flush();

            //redirect to 
            return $this->redirectToRoute('app_user');
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createview()

        ]);
    }
}
