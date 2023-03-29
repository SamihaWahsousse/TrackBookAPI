<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\BorrowBook;
use App\Repository\BookRepository;
use App\Repository\BorrowBookRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class BookController extends AbstractController
{
    #[Route('/api/v1/books', name: 'get_books', methods: ["GET"])]
    public function getBook(SerializerInterface $serializer): Response
    {
        $books = $this->getDoctrine()
            ->getRepository(Book::class)
            ->findAll();

        $json = $serializer->serialize($books, 'json', ['groups' => 'book:read']);
        $response = new Response($json, 200, ["Content-Type" => "application/json"]);
        return $response;
    }

    //display details for one book /api/v1/book/1 done
    //user borrow a book 
    #[Route('/api/v1/books/{id}', name: 'post_books', methods: ["POST"])]
    public function postBook($id, SerializerInterface $serializer): Response
    {
        $books = $this->getDoctrine()
            ->getRepository(Book::class)
            ->find(["id" => $id]);
        if (!$books) {
            return $this->json(["error" => "Book not found!"], 200);
        }

        $json = $serializer->serialize($books, 'json', ['groups' => 'book:read']);
        $response = new Response($json, 200, ["Content-Type" => "application/json"]);
        return $response;
    }


    #[Route('/api/v1/books/{id_book}/borrowBook', name: 'post_books_borrow', methods: ["POST"])]
    public function borrowBook($id_book, SerializerInterface $serializer, Request $request, BookRepository $bookRepository, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $book   = $bookRepository->find($id_book);
        if (!$book) {
            return $this->json(["Message" => "Book doesn't exist"], Response::HTTP_BAD_REQUEST);
        }

        if (!$book->isIsAvailable()) {
            return $this->json(["Message" => "Book is already borrowed"], Response::HTTP_ACCEPTED);
        } else {

            $userId = $request->get("id");
            $user   = $userRepository->find($userId);


            $borrow = new BorrowBook();
            $borrow->setBook($book);
            $borrow->setUser($user);
            $borrow->setBorrowDate(new DateTime());
            $book->setIsAvailable(false);

            $em->persist($borrow);
            $em->flush();
            return $this->json(["Message" => "Book borrowed!"]);
        }
    }

    #[Route('/api/v1/books/{id_book}/return', name: 'post_books_return', methods: ["POST"])]
    public function returnBook($id_book, SerializerInterface $serializer, Request $request, BookRepository $bookRepository, UserRepository $userRepository, EntityManagerInterface $em, BorrowBookRepository $borrowBookRepository): Response
    {
        $book   = $bookRepository->find($id_book);
        $userId = $request->get("id");
        $user   = $userRepository->find($userId);


        if (!$book->isIsAvailable()) {
            $borrow = $borrowBookRepository->findOneBy(["id" => $book, "id" => $user]);
            $timeBorrow = date("Y-m-d H:i:s");
            $testTime = $borrow->setReturnDate(new DateTime($timeBorrow));
            // dd($testTime);
            $book->setIsAvailable(true);
            $em->persist($book);
            $em->flush();

            return $this->json(["message" => "Book returned"]);
        } else {
            return $this->json(["Message" => "Book is available", Response::HTTP_BAD_REQUEST]);
        }
    }
}
/*le premier passage (emprunter le livre)
-> emprunter un book(action à créer dans la table borrowBook)
*identifier l'utilisateur qui souhaite faire l'action emprunter
*identifier le book via son id sur lequel l'utilusateur souhaite faire l'action emprunter
*on crée un nouveau Objet BorrowBook avec tous ses attributs setUser,setBook,setisAvailable (pour le book)

*le deuxième passage (retourner le livre)
-> je vérifie l'id de l'utilisateur 
-> je recherche l'id du livre objet de l'action retourner,je récupère l'id du spotBooks (àtravers le scan de son qrcode),
je vérifie son état si isAvailable == false
alors changer son etat setIsAvailable==true sinon laisser son état comme il est et afficher erreur 
à l'utilisateur "chemin inexistant"
*/