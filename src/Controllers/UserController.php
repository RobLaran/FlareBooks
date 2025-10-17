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

class UserController extends Controller {
    private $userModel;
    private $userService;

    public function __construct() {
        $this->userModel = new User();
        $this->userService = new UserService($this->userModel);
    }

    public function index() {
        

        $this->view('/user/index', [ 
            "title" => "Landing Page"
        ], "landing_page");
    }

    public function features() {
        $this->view('/user/features', [ 
            "title" => "Features"
        ], "landing_page");
    }

    public function about() {
        $this->view('/user/about', [ 
            "title" => "About Us"
        ], "landing_page");
    }

    public function contact() {
        $this->view('/user/contact', [ 
            "title" => "Contact Us"
        ], "landing_page");
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

        $this->view('/user/profile', [ 
            "title" => $info['name'] . "'s " . "Profile", 
            "info" => $info,
            "old" => $old
        ]);
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
                RedirectHelper::withFlash('error', 'Failed to update user details', '/profile/' . $id);
            }
            
            $this->userService->refreshUser($id);
            RedirectHelper::withFlash('success', 'User Details successfully updated', '/profile/' . $id);
        } catch(ValidationException $errors) {
            RedirectHelper::withFlash('errors', $errors->getErrors(), '/profile/' . $id);
        } catch(Exception $error) {
            RedirectHelper::withFlash('error', $error->getMessage(), '/profile/' . $id);
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
                RedirectHelper::withFlash('error', 'Failed to update password', '/profile/' . $id);
            }
            
            $this->userService->refreshUser($id);
            RedirectHelper::withFlash('success', 'Password successfully updated', '/profile/' . $id);
        } catch(ValidationException $errors) {
            RedirectHelper::withFlash('errors', $errors->getErrors(), '/profile/' . $id);
        } catch(Exception $error) {
            RedirectHelper::withFlash('error', $error->getMessage(), '/profile/' . $id);
        }
    }
}