<?php

namespace Services;

use App\Core\Validator;
use App\Core\ValidationException;
use App\Models\BorrowedBook;

class BorrowedBookService {
    protected $transactionModel;

    public function __construct(BorrowedBook $transactionModel) {
        $this->transactionModel = $transactionModel;
    }

    public function validateTransaction($transaction) {
        Validator::validate($transaction['book_id'], 'Book', [ 'required', 'numeric' ]);
        Validator::validate($transaction['borrower_code'], 'Borrower', [ 'required' ]);
        Validator::validate($transaction['due_date'], 'Due date', [ 'required', 'date' ]);
        
        if(Validator::hasErrors()) throw new ValidationException(Validator::getErrors());
    }

    public function process($transaction) {
        return $this->transactionModel->addTransaction($transaction);
    }

}