<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\BorrowBook;
use App\Entity\Category;
use App\Repository\BookRepository;
use App\Repository\BorrowBookRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class BookController extends AbstractController
{
    #[Route('/api/v1/books', name: 'get_all_books', methods: ["GET"])]
    public function getAllBooks(SerializerInterface $serializer, ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Book::class);
        $books = $repository->findAll();;

        $json = $serializer->serialize($books, 'json', ['groups' => 'book:read']);
        $response = new Response($json, 200, ["Content-Type" => "application/json"]);
        return $response;
    }

    //display details for one book /api/v1/book/1 done
    #[Route('/api/v1/books/{id}', name: 'get_book', methods: ["GET"])]
    public function getBook($id, SerializerInterface $serializer, BookRepository $bookRepository): Response
    {
        $books   = $bookRepository->find($id);
        if (!$books) {
            return $this->json(["error" => "Book not found!"], 200);
        }

        $json = $serializer->serialize($books, 'json', ['groups' => 'book:read']);
        $response = new Response($json, 200, ["Content-Type" => "application/json"]);
        return $response;
    }

    //user borrow a book 
    #[Route('/api/v1/books/{id_book}/borrowBook', name: 'post_books_borrow', methods: ["POST"])]
    public function borrowBook($id_book, Request $request, BookRepository $bookRepository, UserRepository $userRepository, EntityManagerInterface $em): Response
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
            $em->persist($book);
            $em->flush();
            return $this->json(["Message" => "Book borrowed!"]);
        }
    }


    //user return a book 
    #[Route('/api/v1/books/{id_book}/return', name: 'post_books_return', methods: ["POST"])]
    public function returnBook($id_book, Request $request, BookRepository $bookRepository, UserRepository $userRepository, EntityManagerInterface $em, BorrowBookRepository $borrowBookRepository): Response
    {
        $book   = $bookRepository->find($id_book);
        $userId = $request->get("id");
        $user   = $userRepository->find($userId);


        if (!$book->isIsAvailable()) {
            $borrow = $borrowBookRepository->findOneBy(["book" => $book, "user" => $user, 'returnDate' => null]);

            $time = new DateTimeImmutable();
            $borrow->setReturnDate($time);
            $book->setIsAvailable(true);
            $em->persist($borrow);
            $em->persist($book);
            $em->flush();

            return $this->json(["Message" => "Book returned"]);
        } else {
            return $this->json(["Message" => "Book is available", Response::HTTP_BAD_REQUEST]);
        }
    }

    //Dispaly all available Books categories
    #[Route('/api/v1/category', name: 'categories', methods: ["GET"])]
    public function getCategories(CategoryRepository $categoryRepository, SerializerInterface $serializer): Response
    {
        $rep = $categoryRepository->findAll();
        $json = $serializer->serialize($rep, 'json', ['groups' => 'category:read']);
        $response = new Response($json, 200, ["Content-Type" => "application/json"]);
        return $response;
    }

    //Dispaly a Book category by category-id
    #[Route('/api/v1/category/{id}', name: 'books_of_category', methods: ["GET"])]
    public function getBooksCategory(Category $category, BookRepository $bookRepository, SerializerInterface $serializer): Response
    {
        $books = $bookRepository->findBy(["category" => $category]);
        $json = $serializer->serialize($books, 'json', ['groups' => 'book:read']);
        $response = new Response($json, 200, ["Content-Type" => "application/json"]);
        return $response;
    }

    //Dispaly history list of borrowed/returned Books for a user 
    #[Route('/api/v1/borrowHistory', name: 'borrow_history', methods: ["POST"])]
    public function getBorrowedHistory(Request $request, SerializerInterface $serializer, BorrowBookRepository $borrowBookRepository, UserRepository $userRepository): Response
    {
        $userId = $request->get("id");
        $user   = $userRepository->find($userId);


        $borrowedBook = $borrowBookRepository->findBy(["user" => $user]);
        $json = $serializer->serialize($borrowedBook, 'json', ['groups' => 'history:read']);
        $response = new Response($json, 200, ["Content-Type" => "application/json"]);
        return $response;
    }
}

/*Pseudo-code pour l'opération emprunter et retourner un livre 

le premier passage (emprunter le livre)
-> emprunter un book(action à créer dans la table borrowBook)
*identifier l'utilisateur qui souhaite faire l'action emprunter
*identifier le book via son id sur lequel l'utilusateur souhaite faire l'action emprunter
*on crée un nouveau Objet BorrowBook avec tous ses attributs setUser,setBook,setisAvailable (pour le book)

*le deuxième passage (retourner le livre)
-> je vérifie l'id de l'utilisateur 
-> je recherche l'id du livre objet de l'action retourner,je récupère l'id du spotBooks (à travers le scan de son qrcode),
je vérifie son état si isAvailable == false
alors changer son etat setIsAvailable==true sinon laisser son état comme il est et afficher erreur 
à l'utilisateur "chemin inexistant"
*/