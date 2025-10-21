<?php 

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Sanitizer;
use App\Core\ValidationException;
use App\Core\Validator;
use App\Models\Genre;
use App\Models\User;
use Exception;
use Helpers\RedirectHelper;
use Helpers\SessionHelper;
use Services\UserService;

class AdminController extends Controller {
    private $bookController;
    private $userModel;
    private $userService;
    private $genreModel;


    public function __construct() {
        $this->bookController = new BooksController();
        $this->userModel = new User();
        $this->genreModel = new Genre();
        $this->userService = new UserService($this->userModel);
    }

    public function dashboard() {
        $this->view('/admin/dashboard', [ 
            "title" => "Admin Dashboard"
        ], "layout");
    }

    public function staffs() {
        $results = $this->userModel->getAllUsers();
        $staffs = $this->userModel->format($results);

        $this->view('/admin/staffs', [ 
            "title" => "Staffs",
            "staffs" => $staffs
        ], "layout");
    }

    public function addStaff() {
        try {
            $staff = [
                "name" => Sanitizer::clean($_POST['name']),
                "username" => Sanitizer::clean($_POST['username']),
                "email" => Sanitizer::clean($_POST['email']),
                "password" => Sanitizer::clean($_POST['password'])
            ];

            Validator::validate($staff['name'], 'Staff name', ['required']);
            Validator::validate($staff['username'], 'Staff username', ['required']);
            Validator::validate($staff['email'], 'Staff email', ['required']);
            Validator::validate($staff['password'], 'Staff password', ['required']);

            if(Validator::hasErrors()) throw new ValidationException(Validator::getErrors());

            if($this->userModel->userExist($staff['username'])) throw new Exception('Staff username already exist');

            $result = $this->userModel->addUser($staff);

            if(!$result) {
                RedirectHelper::withFlash('error', 'Failed to add staff', '/admin/staffs');
            }
            
            RedirectHelper::withFlash('success', 'New staff successfully added', '/admin/staffs');
        } catch(ValidationException $errors) {
            RedirectHelper::withFlash('errors', $errors->getErrors(), '/admin/staffs');
        } catch(Exception $error) {
            RedirectHelper::withFlash('error', $error->getMessage(), '/admin/staffs');
        }
    }

    public function profile($id) {
        $info = [
            "id" => $_SESSION['user']['id'],
            "name" => $_SESSION['user']['name'],
            "email" => $_SESSION['user']['email'],
            "role" => $_SESSION['user']['role'],
            "image" => $_SESSION['user']['image'] ?: "",
        ];

        $old = SessionHelper::getFlash('previousUserInput');

        $this->view('/admin/profile', [ 
            "title" => $info['name'] . "'s " . "Profile", 
            "info" => $info,
            "old" => $old
        ], "layout");
    }

    public function update($id) {
        try {
            $user = $this->userModel->findUserById($id);
            
            $updatedUser = [
                "name" => Sanitizer::clean($_POST['name']),
                "email" => Sanitizer::clean($_POST['email']),
                "image" => Sanitizer::clean($_POST['image'] ?: ($_POST['image_url'] ?: $user['image']))
            ];
            
            $this->userService->validateUpdatedUser($updatedUser);
            $result = $this->userModel->updateUserInfo($id, $updatedUser);

            if(!$result) {
                RedirectHelper::withFlash('error', 'Failed to update user details', '/admin/profile/' . $id);
            }
            
            $this->userService->refreshUser($id);
            RedirectHelper::withFlash('success', 'User Details successfully updated', '/admin/profile/' . $id);
        } catch(ValidationException $errors) {
            RedirectHelper::withFlash('errors', $errors->getErrors(), '/admin/profile/' . $id);
        } catch(Exception $error) {
            RedirectHelper::withFlash('error', $error->getMessage(), '/admin/profile/' . $id);
        }
    }

    public function changePassword($id) {
        try {
            $updatedPassword = [
                "newPassword" => Sanitizer::clean($_POST['newPassword']),
                "confirmPassword" => Sanitizer::clean($_POST['confirmPassword']),
            ];

            $this->userService->validatePassword($updatedPassword['newPassword'], "New Password");
            $this->userService->validatePassword($updatedPassword['confirmPassword'], "Confirm New Password");
            $this->userService->matchPassword($updatedPassword['newPassword'], $updatedPassword['confirmPassword']);
            $result = $this->userModel->updatePassword($id, $updatedPassword['newPassword']);

            if(!$result) {
                RedirectHelper::withFlash('error', 'Failed to update password', '/admin/profile/' . $id);
            }
            
            $this->userService->refreshUser($id);
            RedirectHelper::withFlash('success', 'Password successfully updated', '/admin/profile/' . $id);
        } catch(ValidationException $errors) {
            RedirectHelper::withFlash('errors', $errors->getErrors(), '/admin/profile/' . $id);
        } catch(Exception $error) {
            RedirectHelper::withFlash('error', $error->getMessage(), '/admin/profile/' . $id);
        }
    }
}