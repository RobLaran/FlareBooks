<?php
namespace App\Controllers;

use App\Core\Controller;

class BorrowedBooksController extends Controller {

    public function __construct() {
        $this->title = "Borrowed Books";
    }
    public function index() {
        $this->view("borrowed_books", [ "title" => $this->title ]);
    }
}
