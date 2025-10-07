<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Sanitizer;
use App\Core\ValidationException;
use App\Core\Validator;
use App\Models\Book;
use App\Models\BorrowedBook;
use App\Models\ReturnedBook;
use Exception;
use Helpers\RedirectHelper;

class ReturnsController extends Controller {
    private $transactionModel;
    private $returnedBookModel;

    public function __construct() {
        $this->title = "Returns";
        $this->transactionModel = new BorrowedBook();
        $this->returnedBookModel = new ReturnedBook();
    }

    public function add() {
        try {
            Validator::validate($_POST['transaction_id'], 'Borrowed book', [ 'required' ]);

            if(Validator::hasErrors()) throw new ValidationException(Validator::getErrors());

            $returnedBook = $this->transactionModel->getTransactionById($_POST['transaction_id']);

            if(empty($returnedBook)) {
                throw new Exception('No transaction found');
            }

            $result = $this->returnedBookModel->addReturnedBook($returnedBook);

            if(!$result) {
                RedirectHelper::withFlash('error', 'Failed to return book', '/returns');
            }

            $this->transactionModel->deleteTransaction($_POST['transaction_id']);
            
            RedirectHelper::withFlash('success', 'Book successfully returned', '/returns');
        } catch(ValidationException $errors) {
            RedirectHelper::withFlash('errors', $errors->getErrors(), '/returns');
        } catch(Exception $error) {
            RedirectHelper::withFlash('error', $error->getMessage(), '/returns');
        }
    }

    public function index() {
        $returnedBooks = $this->returnedBookModel->getReturnedBooks();
        $transactions = $this->transactionModel->getAllTransactions(); 

        $this->view("/user/returns", [ 
            "title" => $this->title,
            "transactions" => $transactions,
            "data" => $returnedBooks
        ]);
    }

    public function search() {
        $query = $_GET['q'] ?? '';

        $transactions = $this->transactionModel->searchTransaction($query);

        header('Content-Type: application/json');
        echo json_encode($transactions);
        exit;
    }
}
