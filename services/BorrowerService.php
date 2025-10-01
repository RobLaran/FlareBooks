<?php

namespace Services;

use App\Models\Borrower;
use App\Core\Validator;
use App\Core\ValidationException;
use Helpers\SessionHelper;

class BorrowerService {
    protected $borrowerModel;

    public function __construct(Borrower $borrowerModel) {
        $this->borrowerModel = $borrowerModel;
    }

    public function registerBorrower($borrower): bool {
        SessionHelper::setFlash('previousBorrowerInput', $borrower);

        $errors = [];

        if($error = Validator::required($borrower['fname'], 'First Name')) $errors[] = $error;
        if($error = Validator::required($borrower['lname'], 'Last Name')) $errors[] = $error;

        if($error = Validator::required($borrower['email'], 'Email')) $errors[] = $error;
        else if($error = Validator::email($borrower['email'])) $errors[] = $error;

        if(!empty($borrower['phone']) && $error = Validator::numeric($borrower['phone'], 'Phone Number')) $errors[] = $error;

        if($errors) throw new ValidationException($errors);

        $result = $this->borrowerModel->addBorrower($borrower);

        if($result) SessionHelper::unsetFlash('previousBorrowerInput');

        return $result;
    }
}