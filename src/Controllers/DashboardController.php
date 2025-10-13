<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Book;
use App\Models\BorrowedBook;
use App\Models\Borrower;

class DashboardController extends Controller {
    private $bookModel;
    private $borrowedBookModel;
    private $borrowerModel; 


    public function __construct() {
        $this->title = "Dashboard";
        $this->bookModel = new Book();
        $this->borrowedBookModel = new BorrowedBook();
        $this->borrowerModel = new Borrower();
    }
    public function index() {
        $bookCount = $this->bookModel->getTotalBooks();
        $borrowedBookCount = $this->borrowedBookModel->getTotalTransactions();
        $borrowerCount = $this->borrowerModel->getTotalBorrowersByParam(
            "status = 'active'"
        );
        $overdueBookCount = $this->borrowedBookModel->getTotalTransactionsByParam(
            "due_date < CURDATE()"
        );
        
        $this->view("/user/dashboard", [ 
            "title" => $this->title, 
            "bookCount" => $bookCount,
            "borrowedBookCount" => $borrowedBookCount,
            "borrowerCount" => $borrowerCount,
            "overdueBookCount" => $overdueBookCount,

        ]);
    }
}
