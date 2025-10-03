<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Book;
use App\Models\BorrowedBooks;
use App\Models\Borrower;

class BorrowedBooksController extends Controller {
    private $borrowedBookModel;
    private $bookModel;
    private $borrowerModel;

    public function __construct() {
        $this->title = "Borrowed Books";
        $this->borrowedBookModel = new BorrowedBooks();
        $this->bookModel = new Book();
        $this->borrowerModel = new Borrower();
    }
    public function index() {
        $books = $this->bookModel->getAllBooks();
        $borrowers = $this->borrowerModel->getAllBorrowers();

        $this->view("/user/borrowed_books", [ 
            "title" => $this->title ,
            "books" => $books,
            "borrowers" => $borrowers
        ]);
    }

    public function add() {
        dd($_POST);
    }

    public function searchBook() {
        $query = $_GET['q'] ?? '';

        $books = $this->bookModel->searchBooks($query);

        header('Content-Type: application/json');
        echo json_encode($books);
        exit;
    }

    public function searchBorrower() {
        $query = $_GET['q'] ?? '';

        $borrowers = $this->borrowerModel->searchBorrowers($query);

        header('Content-Type: application/json');
        echo json_encode($borrowers);
        exit;
    }
}
