<?php 

namespace App\Controllers;

use App\Core\Controller;

class UserController extends Controller {
    public function profile($id) {
        $this->view('/user/profile', [ "title" => $_SESSION['user']['name'] . "'s " . "Profile" ]);
    }
}