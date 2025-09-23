<?php
namespace App\Controllers;

use App\Core\Controller;

class UserController extends Controller {

    public function __construct() {
        $this->title = "FlareBooks";
    }
    public function index() {
        $this->view("auth/user/index", [ "title" => $this->title ], "auth_layout");
    }
}
