<?php
namespace App\Controllers;

use App\Core\Controller;

class DashboardController extends Controller {

    public function __construct() {
        $this->title = "Dashboard";
    }
    public function index() {
        $this->view("dashboard", [ "title" => $this->title ]);
    }
}
