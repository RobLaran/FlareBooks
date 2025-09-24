<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use Exception;
use Helpers\RedirectHelper;

class AuthController extends Controller {

    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function loginUser() {
        try {
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                if(empty($_POST['email'])) {
                    throw new Exception('Email required');
                } else if(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) {
                    throw new Exception('Invalid Email');
                }

                if(empty($_POST['password'])) {
                    throw new Exception('Empty password');
                }

                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
            } 

            $this->view("auth/user/index", [ "title" => $this->title ], "auth_layout");
        } catch (Exception $error) {
            RedirectHelper::withFlash('error', $error->getMessage(), '/auth/login');
        }
    }

    public function loginAdminForm(): void {
        $this->view("auth/admin/index", [ "title" => $this->title ], "auth_layout");
    }

    public function loginAdmin() {
        try {
            if(empty($_POST['email'])) {
                throw new Exception('Email required');
            } else if(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) {
                throw new Exception('Invalid Email');
            }

            if(empty($_POST['password'])) {
                throw new Exception('Empty password');
            }

            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);


            $admin = $this->userModel->getUser($email);

            RedirectHelper::withFlash('success', 'login success', '/auth/login/admin');
        } catch(Exception $error) {
            RedirectHelper::withFlash('error', $error->getMessage(), '/auth/login/admin');
        }
    }


}
