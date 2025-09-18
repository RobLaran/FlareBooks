<?php
namespace App\Controllers;

use App\Core\Controller;

class ReportsController extends Controller {

    public function __construct() {
        $this->title = "Reports";
    }
    public function index() {
        $this->view("reports", [ "title" => $this->title ]);
    }
}
