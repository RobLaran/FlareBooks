<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Sanitizer;
use App\Core\ValidationException;
use App\Models\Book;
use App\Models\BorrowedBook;
use App\Models\Borrower;
use Exception;
use Helpers\RedirectHelper;
use Services\BorrowedBookService;

class BorrowedBooksController extends Controller {
    private $transactionModel;
    private $bookModel;
    private $borrowerModel;
    private $transactionService;

    public function __construct() {
        $this->title = "Borrowed Books";
        $this->transactionModel = new BorrowedBook();
        $this->bookModel = new Book();
        $this->borrowerModel = new Borrower();
        $this->transactionService = new BorrowedBookService($this->transactionModel);
    }
    public function index() {
        $transactions = [];
        $books = $this->bookModel->getAllBooks();
        $borrowers = $this->borrowerModel->getAllBorrowers();

        $params = $this->getRequestParams(
            [
                "sortBy" => "first_name"
            ]
        );

        if($params['limit'] == 'all') {
            $transactions = $this->transactionModel->getAllTransactions($params['sortBy'], $params['sortDir']); 
            $totalTransactions = $this->transactionModel->getTotalTransactions($params['search']);
            $totalPages = 1;
            $params['limit'] = $totalTransactions;
        } else {
            $offset = ($params['page'] - 1) * $params['limit'];
            $transactions = $this->transactionModel->getPaginatedTransactions($params['limit'], $offset, $params['sortBy'], $params['sortDir'], $params['search']);
            $totalTransactions = $this->transactionModel->getTotalTransactions($params['search']);
            $totalPages = ceil($totalTransactions / $params['limit']);
        }

        $columns = [
            ["field" => "book_info", "name" => "Book Info", "sortable" => false],
            ["field" => "first_name", "name" => "First Name", "sortable" => true],
            ["field" => "last_name", "name" => "Last Name", "sortable" => true],
            ["field" => "borrow_date", "name" => "Borrow Date", "sortable" => false],
            ["field" => "due_date", "name" => "Due Date", "sortable" => false],
            ["field" => "status", "name" => "Status", "sortable" => false],
            ["field" => "actions", "name" => "Actions", "sortable" => false],
        ];

        $this->view("/user/borrowed_books", array_merge($params, [ 
            "title" => $this->title ,
            "books" => $books,
            "borrowers" => $borrowers,
            "items" => $transactions,
            "totalItems" => $totalTransactions,
            "totalPages" => $totalPages,
            "columns" => $columns
            
        ]));
    }

    public function add() {
        try {
            $transaction = [
                "due_date" => Sanitizer::clean($_POST['due_date']),
                "book_id" => Sanitizer::clean($_POST['book_id']),
                "borrower_code" => Sanitizer::clean($_POST['borrower_code']),
            ];

            $this->transactionService->validateTransaction($transaction);
            $result = $this->transactionService->process($transaction);

            if(!$result) {
                RedirectHelper::withFlash('error', 'Failed to add transaction', '/borrowed-books');
            }
            
            RedirectHelper::withFlash('success', 'New transaction successfully added', '/borrowed-books');
        } catch(ValidationException $errors) {
            RedirectHelper::withFlash('errors', $errors->getErrors(), '/borrowed-books');
        } catch(Exception $error) {
            RedirectHelper::withFlash('error', $error->getMessage(), '/borrowed-books');
        }
    }

    public function delete($id) {
        $result = $this->transactionModel->deleteTransaction($id);

        if(!$result) {
            RedirectHelper::withFlash('error', 'Failed to delete transaction', '/borrowed-books');
        }

        RedirectHelper::withFlash('success', 'Transaction successfully deleted', '/borrowed-books');
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
