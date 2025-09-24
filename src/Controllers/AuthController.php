<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\ValidationException;
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
            if(isset($_SESSION['user'])) {
                header('Location: ' . routeTo('/dashboard'));
                exit;
            }

            if(!isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
                $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
                $errors = [];

                if(empty($username)) {     
                    $errors[] = 'Enter Username';
                } 
                
                if(empty($email)) {    
                    $errors[] = 'Email required';
                } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {    
                    $errors[] = 'Invalid Email';
                }

                if(empty($password)) {      
                    $errors[] = 'Empty password';
                } 

                if($errors) {
                    throw new ValidationException($errors);
                }

                $user = $this->userModel->getUser($email, $username);

                if($user === null) {
                    throw new ValidationException([ 'Wrong username or invalid email' ]);
                }

                if(isset($user['password']) && $user['password'] !== $password) {
                    throw new ValidationException([ 'Wrong password' ]);
                }

                if(isset($user['role']) && $user['role'] === 'admin') {
                    throw new ValidationException([ 'Admins must log in via the admin login page' ]);
                }

                // 🔹 Store session
                $_SESSION['user'] = [
                    'id'       => $user['user_id'],
                    'username' => $user['username'],
                    'email'    => $user['email'],
                    'role'     => $user['role'] ?: 'user'
                ];

                RedirectHelper::withFlash('success', 'Login success', '/dashboard');
            } 

            $this->view("auth/user/index", [ "title" => $this->title ], "auth_layout");
        } catch(ValidationException $errors) {
            RedirectHelper::withFlash('errors', $errors->getErrors(), '/auth/login');
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
