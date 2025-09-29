<?php
namespace App\Controllers;

use App\Core\Controller;

class ReturnsController extends Controller {

    public function __construct() {
        $this->title = "Returns";
    }
    public function index() {
        $this->view("/user/returns", [ "title" => $this->title ]);
    }
}
