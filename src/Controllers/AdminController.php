<?php 

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Sanitizer;
use App\Core\ValidationException;
use App\Models\User;
use Exception;
use Helpers\RedirectHelper;
use Helpers\SessionHelper;
use Services\UserService;

class AdminController extends Controller {

    public function __construct() {
    }

    public function dashboard() {
        $this->view('/admin/dashboard', [ 
            "title" => "Admin Dashboard"
        ], "layout");
    }

    public function books() {
        $this->view('/admin/books', [ 
            "title" => "Books"
        ], "layout");
    }

    public function genres() {
        $this->view('/admin/genres', [ 
            "title" => "Genres"
        ], "layout");
    }

    public function staffs() {
        $this->view('/admin/staffs', [ 
            "title" => "Staffs"
        ], "layout");
    }

    public function reports() {
        $this->view('/admin/reports', [ 
            "title" => "Reports"
        ], "layout");
    }

    public function profile($id) {
        $this->view('/admin/profile', [ 
            "title" => "Admin Profile"
        ], "layout");
    }
}