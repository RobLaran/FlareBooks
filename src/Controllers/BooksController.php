<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Book;
use App\Models\Genre;

class BooksController extends Controller {
    private $bookModel;

    public function __construct() {
        $this->title = "Book List";
        $this->bookModel = new Book();
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
            $genreModel = new Genre();

            foreach ($books as &$book) {
                $book['genre'] = $genreModel->getBookGenre($book['genre_id']);
            }
            unset($book); // break the reference

            $totalBooks = $this->bookModel->getTotalBooks($params['search']);
            $totalPages = ceil($totalBooks / $params['limit']);
        }

        $this->view("books", array_merge($params , [
            "title" => $this->title,
            "books" => $books,
            "totalBooks" => $totalBooks,
            "totalPages" => $totalPages
        ]));
    }
}
