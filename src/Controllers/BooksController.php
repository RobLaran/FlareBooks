<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Book;
use App\Models\Genre;

use Exception;
use Helpers\RedirectHelper;

class BooksController extends Controller {
    private $bookModel;
    private $genreModel;

    public function __construct() {
        $this->title = "Book List";
        $this->bookModel = new Book();
        $this->genreModel = new Genre();
    }
    public function index() {
        $params = $this->getRequestParams(
            [
                "sortBy" => "author"
            ]
        );

        if($params['limit'] == 'all') {
            $books = $this->bookModel->getAllBooks($params['sortBy'], $params['sortDir']);
            $totalBooks = $this->bookModel->getTotalBooks($params['search']);
            $totalPages = 1;
            $params['limit'] = $totalBooks;
        } else {
            $offset = ($params['page'] - 1) * $params['limit'];

            $books = $this->bookModel->getPaginatedBooks($params['limit'], $offset, $params['sortBy'], $params['sortDir'], $params['search']);

            foreach ($books as &$book) {
                $book['genre'] = $this->genreModel->getBookGenre($book['genre_id']);
            }
            unset($book);

            $totalBooks = $this->bookModel->getTotalBooks($params['search']);
            $totalPages = ceil($totalBooks / $params['limit']);
        }

        $this->view("books/index", array_merge($params , [
            "title" => $this->title,
            "books" => $books,
            "totalBooks" => $totalBooks,
            "totalPages" => $totalPages
        ]));
    }

    public function create() {
        $genres = $this->genreModel->getAllGenres();

        $this->view("books/add-book", [ 
            "title" => "Add Book", 
            "genres" => $genres 
        ]);
    }

    public function add() {
        try {
            $result = $this->bookModel->addBook([
                "ISBN" => $_POST['ISBN'],
                "author" => $_POST['author'],
                "title" => $_POST['title'],
                "publisher" => $_POST['publisher'],
                "genre" => $_POST['genre'],
                "quantity" => $_POST['quantity'],
                "status" => $_POST['status'],
                "image" => $_POST['image'] ?: ($_POST['image_url'] ?: null)
            ]);

            if(!$result) {
                RedirectHelper::withFlash('error', 'Failed to add book', '/books/add');
            }
            
            RedirectHelper::withFlash('success', 'New book successfully added', '/books');
        } catch(Exception $error) {
            RedirectHelper::withFlash('error', $error->getMessage(), '/books/add');
        }
    }

    public function edit($id) {
        try {
            $book = $this->bookModel->getBook($id);
            $genres = $this->genreModel->getAllGenres();

            $this->view("books/edit-book", [ 
                "title" => "Edit Book",
                "book" => $book,
                "genres" => $genres
            ]);
        } catch (Exception $error) {
            RedirectHelper::withFlash('error', $error->getMessage(), '/books/edit');
        }
    }

    public function update() {
        header("Location: " . routeTo('/books'));
        exit;
    }

    public function delete($id) {
        $result = $this->bookModel->removeBook($id);

        if(!$result) {
            RedirectHelper::withFlash('error', 'Failed to remove book', '/books');
        }

        RedirectHelper::withFlash('success', 'Book successfully removed', '/books');
    }
}
