<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Book;
use App\Models\Genre;

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
            unset($book); // break the reference

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
        dd($_POST);

        header("Location: " . routeTo('/books'));
        exit;
    }

    public function edit($id) {
        $book = $this->bookModel->getBook($id);

        $this->view("books/edit-book", [ "title" => "Edit Book" ]);
    }

    public function update() {
        header("Location: " . routeTo('/books'));
        exit;
    }

    public function delete() {

    }
}
