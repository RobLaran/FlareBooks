<?php
namespace App\Controllers;

use App\Core\Controller;

class OverdueBooks extends Controller {

    public function __construct() {
        $this->title = "Overdue Books";
    }
    public function index() {
        $this->view("overdue-books", [ "title" => $this->title ]);
    }
}
