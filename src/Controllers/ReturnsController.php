<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Book;
use App\Models\BorrowedBook;

class ReturnsController extends Controller {
    private $transactionModel;

    public function __construct() {
        $this->title = "Returns";
        $this->transactionModel = new Book();
    }
    public function index() {
        $transactions = $this->transactionModel->getAllBooks(); 


        $this->view("/user/returns", [ 
            "title" => $this->title,
            "data" => [
                "Book Info" => [
                    "Image" => "some image",
                    "ISBN" => "asdasd",
                    "Author" => "some author",
                    "Title" => "some tiotle"
                ],
                "Borrower" => "some borrower",
                "Borrow Date" => "some date",
                "Due Date" => "some datge",
                "Status" => "ssome status"

            ]
        ]);
    }
}
