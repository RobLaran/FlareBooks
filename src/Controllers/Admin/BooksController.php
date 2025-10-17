<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Sanitizer;
use App\Core\ValidationException;
use App\Models\Book;
use App\Models\Genre;

use Exception;
use Helpers\RedirectHelper;
use Helpers\SessionHelper;
use Services\BookService;

class BooksController extends Controller {
    private $bookModel;
    private $genreModel;
    private $bookService;

    public function __construct() {
        $this->title = "Books";
        $this->bookModel = new Book();
        $this->genreModel = new Genre();
        $this->bookService = new BookService($this->bookModel);
    }

    public function index() {
        $books = $this->bookModel->getAllBooks();

        $this->view("/admin/books/index", [
            "title" => $this->title,
            "books" => $books
        ]);
    }

    public function create() {
        $genres = $this->genreModel->getAllGenres();
        $old = SessionHelper::getFlash('previousBorrowerInput');

        $this->view("/admin/books/add", [ 
            "title" => "Add Book", 
            "genres" => $genres,
            "old" => $old 
        ]);
    }

    public function search() {
        $query = $_GET['q'] ?? '';

        $books = $this->bookModel->searchBooks($query);

        header('Content-Type: application/json');
        echo json_encode($books);
        exit;
    }

    public function add() {
        try {
            $book = [
                "ISBN" => Sanitizer::clean($_POST['ISBN']),
                "author" => Sanitizer::clean($_POST['author']),
                "title" => Sanitizer::clean($_POST['title']),
                "publisher" => Sanitizer::clean($_POST['publisher']),
                "genre" => Sanitizer::clean($_POST['genre']),
                "quantity" => Sanitizer::clean($_POST['quantity'] ?: '0'),
                "status" => Sanitizer::clean($_POST['status']),
                "image" => Sanitizer::clean($_POST['image'] ?: ($_POST['image_url'] ?: ''))
            ];

            $this->bookService->validateBook($book);
            $result = $this->bookService->insertBook($book);

            if(!$result) {
                RedirectHelper::withFlash('error', 'Failed to add book', '/admin/books/add');
            }
            
            RedirectHelper::withFlash('success', 'New book successfully added', '/admin/books');
        } catch(ValidationException $errors) {
            RedirectHelper::withFlash('errors', $errors->getErrors(), '/admin/books/add');
        } catch(Exception $error) {
            RedirectHelper::withFlash('error', $error->getMessage(), '/admin/books/add');
        }
    }

    public function edit($id) {
        try {
            $book = $this->bookModel->getBook($id);
            $genres = $this->genreModel->getAllGenres();
            $old = SessionHelper::getFlash('previousBorrowerInput');

            $this->view("/admin/books/edit", [ 
                "title" => "Edit Book",
                "book" => $book,
                "genres" => $genres,
                "old" => $old
            ]);
        } catch (Exception $error) {
            RedirectHelper::withFlash('error', $error->getMessage(), '/admin/books/edit');
        }
    }

    public function update($id) {
        try {
            $book = $this->bookModel->getBook($id);

            $updatedBook = [
                "ISBN" => Sanitizer::clean($_POST['ISBN']),
                "author" => Sanitizer::clean($_POST['author']),
                "title" => Sanitizer::clean($_POST['title']),
                "publisher" => Sanitizer::clean($_POST['publisher']),
                "genre" => Sanitizer::clean($_POST['genre']),
                "quantity" => Sanitizer::clean($_POST['quantity'] ?: '0'),
                "status" => Sanitizer::clean($_POST['status']),
                "image" => Sanitizer::clean($_POST['image'] ?: ($_POST['image_url'] ?: $book['image']))
            ];

            $this->bookService->validateBook($updatedBook);
            $result = $this->bookModel->updateBook($id, $updatedBook);

            if(!$result) {
                RedirectHelper::withFlash('error', 'Failed to update book', '/admin/books/edit/' . $id);
            }
            
            RedirectHelper::withFlash('success', 'Book successfully updated', '/admin/books');
        } catch(ValidationException $errors) {
            RedirectHelper::withFlash('errors', $errors->getErrors(), '/admin/books/edit/' . $id);
        } catch(Exception $error) {
            RedirectHelper::withFlash('error', $error->getMessage(), '/admin/books/edit/' . $id);
        }
    }

    public function delete($id) {
        $result = $this->bookModel->removeBook($id);

        if(!$result) {
            RedirectHelper::withFlash('error', 'Failed to remove book', '/admin/books');
        }

        RedirectHelper::withFlash('success', 'Book successfully removed', '/admin/books');
    }
}
