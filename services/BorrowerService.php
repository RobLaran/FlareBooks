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

    public function validateBorrower($borrower): void {
        Validator::validate($borrower['fname'], 'First Name', [ 'required' ]);
        Validator::validate($borrower['lname'], 'Last Name', [ 'required' ]);
        Validator::validate($borrower['email'], 'Email', [ 'required', 'email' ]);

        if(!empty($borrower['phone'])) {
            Validator::validate($borrower['phone'], 'Phone Number', [ 'numeric' ]);
        }

        if(Validator::hasErrors()) throw new ValidationException(Validator::getErrors());
    }

    public function registerBorrower($borrower): bool {
        SessionHelper::setFlash('previousBorrowerInput', $borrower);

        $result = $this->borrowerModel->addBorrower($borrower);

        if($result) SessionHelper::unsetFlash('previousBorrowerInput');

        return $result;
    }
}