<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Sanitizer;
use App\Core\ValidationException;
use App\Models\User;
use Exception;
use Helpers\RedirectHelper;

class AuthController extends Controller {

    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

     public function login() {
            $this->view("auth/index", [], "landing_page");
    }

    public function attemptLogin() {
        try {
            $role = $_POST['role'] ?: '';
            $data = [
                "username" => Sanitizer::clean($_POST['username']),
                "email" => Sanitizer::clean($_POST['email'], 'email'),
                "password" => Sanitizer::clean($_POST['password'])
            ];

             $errors = [];

            if(empty($data['username'])) {     
                $errors[] = 'Enter Username';
            } 
            
            if(empty($data['email'])) {    
                $errors[] = 'Email required';
            } else if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {    
                $errors[] = 'Invalid Email';
            }

            if(empty($data['email'])) {      
                $errors[] = 'Empty password';
            } 

            if($errors) {
                throw new ValidationException($errors);
            }

            if($role === 'librarian') {
                $this->loginUser($data);
            } else if($role === 'admin') {
                $this->loginAdmin($data);
            } else {
                throw new Exception('No role specified');
            }
        } catch(ValidationException $errors) {
            RedirectHelper::withFlash('errors', $errors->getErrors(), '/auth/login');
        } catch (Exception $error) {
            RedirectHelper::withFlash('error', $error->getMessage(), '/auth/login');
        }
    }

    public function loginUser($data) {
        if(isset($_SESSION['user']) && $_SESSION['user']['role'] === 'Librarian') {
            RedirectHelper::to('/dashboard');   
        }

        if(!isset($_SESSION['user']) 
            && !empty($data)
            && $_SERVER['REQUEST_METHOD'] == 'POST') {

            $user = $this->userModel->getUser($data['email'], $data['username']);

            if($user === null) {
                throw new ValidationException([ 'Wrong username or invalid email' ]);
            }

            if($user['password'] !== $data['password']) {
                throw new ValidationException([ 'Wrong password' ]);
            }

            if(isset($user['role']) && $user['role'] != 'user') {
                throw new ValidationException([ 'You must input the user credentials' ]);
            }

            // Store session
            $_SESSION['user'] = [
                'id'       => $user['user_id'],
                'name' => $user['name'],
                'username' => $user['username'],
                'email'    => $user['email'],
                'role'     => $user['role'] == 'user' ? "Librarian" : "Admin", 
                'image'     => $user['image']
            ];

            RedirectHelper::withFlash('success', 'Login success', '/dashboard');
        } 
    }

    public function loginAdmin($data) {
        if(isset($_SESSION['user']) && $_SESSION['user']['role'] === 'Admin') {
            RedirectHelper::to('/admin/dashboard');   
        }

        if(!isset($_SESSION['user']) 
            && !empty($data)
            && $_SERVER['REQUEST_METHOD'] == 'POST') {

            $user = $this->userModel->getUser($data['email'], $data['username']);

            if($user === null) {
                throw new ValidationException([ 'Wrong username or invalid email' ]);
            }

            if($user['password'] !== $data['password']) {
                throw new ValidationException([ 'Wrong password' ]);
            }

            if(isset($user['role']) && $user['role'] != 'admin') {
                throw new ValidationException([ 'You must input the admin credentials' ]);
            }

            // Store session
            $_SESSION['user'] = [
                'id'       => $user['user_id'],
                'name' => $user['name'],
                'username' => $user['username'],
                'email'    => $user['email'],
                'role'     => $user['role'] == 'user' ? "Librarian" : "Admin", 
                'image'     => $user['image']
            ];

            RedirectHelper::withFlash('success', 'Login success', '/admin/dashboard');
        } 
    }

    public function logout() {
        $redirect = '/auth/login'; // default

        if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin') {
            $redirect = '/auth/login/admin';
        }

        $_SESSION = [];
        session_destroy();

        header("Location: " . routeTo($redirect));
        exit;
    }

}
