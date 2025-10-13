<?php

namespace Services;

use App\Core\Validator;
use App\Core\ValidationException;
use App\Models\User;
use Helpers\SessionHelper;

class UserService {
    protected $userModel;

    public function __construct(User $userModel) {
        $this->userModel = $userModel;
    }

    public function validateUpdatedUser($user): void {
        SessionHelper::setFlash('previousUserInput', $user);

        Validator::validate($user['name'], 'Name', [ 'required' ]);
        Validator::validate($user['email'], 'Email', [ 'required', 'email' ]);
        Validator::validate($user['image'], 'image', [ 'required' ]);

        if(Validator::hasErrors()) throw new ValidationException(Validator::getErrors());
    }

    public function validatePassword($password, $fieldName="New Password") {
        Validator::validate($password, $fieldName, [ 'required' ]);

        if(Validator::hasErrors()) throw new ValidationException(Validator::getErrors());
    }

    public function matchPassword($newPassword, $confirmPassword) {
        Validator::validate($newPassword, 'Password', [ 'required', 'match' ], $confirmPassword);
        
        if(Validator::hasErrors()) throw new ValidationException(Validator::getErrors());
    }

    public function refreshUser($id): void {
        $user = $this->userModel->findUserById($id);

        $_SESSION['user'] = [
                    'id'       => $user['user_id'],
                    'name' => $user['name'],
                    'username' => $user['username'],
                    'email'    => $user['email'],
                    'role'     => $user['role'] == 'user' ? "Librarian" : "System Admin", 
                    'image'     => $user['image']
                ];
    }

}