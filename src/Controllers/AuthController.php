<?php
namespace App\Controllers;

use App\Core\Controller;

class AuthController extends Controller {

    public function __construct() {
        $this->title = "FlareBooks";
    }
    public function loginUserForm() {
        $this->view("auth/user/index", [ "title" => $this->title ], "auth_layout");
    }

    public function loginAdminForm() {
        $this->view("auth/admin/index", [ "title" => $this->title ], "auth_layout");
    }
}
