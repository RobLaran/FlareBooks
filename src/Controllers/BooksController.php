<?php
namespace App\Controllers;

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
        $this->title = "Book List";
        $this->bookModel = new Book();
        $this->genreModel = new Genre();
        $this->bookService = new BookService($this->bookModel);
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

        $addButton = [
            "route" => "/books/add",
            "label" => "Add Book"
        ];

        $columns = [
            ["field" => "image", "name" => "Image", "sortable" => false],
            ["field" => "ISBN", "name" => "ISBN", "sortable" => true],
            ["field" => "author", "name" => "Author", "sortable" => true],
            ["field" => "publisher", "name" => "Publisher", "sortable" => true],
            ["field" => "title", "name" => "Title", "sortable" => true],
            ["field" => "genre", "name" => "Genre", "sortable" => true],
            ["field" => "quantity", "name" => "Quantity", "sortable" => true],
            ["field" => "status", "name" => "Status", "sortable" => true],
            ["field" => "actions", "name" => "Actions", "sortable" => false],
        ];


        $this->view("/user/books/index", array_merge($params , [
            "title" => $this->title,
            "items" => $books,
            "totalItems" => $totalBooks,
            "totalPages" => $totalPages,
            "columns" => $columns,
            "addButton" => $addButton
        ]));
    }

    public function create() {
        $genres = $this->genreModel->getAllGenres();
        $old = SessionHelper::getFlash('previousBorrowerInput');

        $this->view("/user/books/add", [ 
            "title" => "Add Book", 
            "genres" => $genres,
            "old" => $old 
        ]);
    }

    public function add() {
        try {
            $book = [
                "ISBN" => Sanitizer::clean($_POST['ISBN']),
                "author" => Sanitizer::clean($_POST['author']),
                "title" => Sanitizer::clean($_POST['title']),
                "publisher" => Sanitizer::clean($_POST['publisher']),
                "genre" => Sanitizer::clean($_POST['genre']),
                "quantity" => Sanitizer::clean($_POST['quantity']),
                "status" => Sanitizer::clean($_POST['status']),
                "image" => Sanitizer::clean($_POST['image'] ?: ($_POST['image_url'] ?: ''))
            ];

            $this->bookService->validateBook($book);
            $result = $this->bookService->insertBook($book);

            if(!$result) {
                RedirectHelper::withFlash('error', 'Failed to add book', '/books/add');
            }
            
            RedirectHelper::withFlash('success', 'New book successfully added', '/books');
        } catch(ValidationException $errors) {
            RedirectHelper::withFlash('errors', $errors->getErrors(), '/books/add');
        } catch(Exception $error) {
            RedirectHelper::withFlash('error', $error->getMessage(), '/books/add');
        }
    }

    public function edit($id) {
        try {
            $book = $this->bookModel->getBook($id);
            $genres = $this->genreModel->getAllGenres();
            $old = SessionHelper::getFlash('previousBorrowerInput');

            $this->view("/user/books/edit", [ 
                "title" => "Edit Book",
                "book" => $book,
                "genres" => $genres,
                "old" => $old
            ]);
        } catch (Exception $error) {
            RedirectHelper::withFlash('error', $error->getMessage(), '/books/edit');
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
                "quantity" => Sanitizer::clean($_POST['quantity']),
                "status" => Sanitizer::clean($_POST['status']),
                "image" => Sanitizer::clean($_POST['image'] ?: ($_POST['image_url'] ?: $book['image']))
            ];

            $this->bookService->validateBook($updatedBook);
            $result = $this->bookModel->updateBook($id, $updatedBook);

            if(!$result) {
                RedirectHelper::withFlash('error', 'Failed to update book', '/books/edit/' . $id);
            }
            
            RedirectHelper::withFlash('success', 'Book successfully updated', '/books');
        } catch(ValidationException $errors) {
            RedirectHelper::withFlash('errors', $errors->getErrors(), '/books/edit/' . $id);
        } catch(Exception $error) {
            RedirectHelper::withFlash('error', $error->getMessage(), '/books/edit/' . $id);
        }
    }

    public function delete($id) {
        $result = $this->bookModel->removeBook($id);

        if(!$result) {
            RedirectHelper::withFlash('error', 'Failed to remove book', '/books');
        }

        RedirectHelper::withFlash('success', 'Book successfully removed', '/books');
    }
}
