<?php

namespace Services;

use App\Core\Validator;
use App\Core\ValidationException;
use App\Models\Book;
use Helpers\SessionHelper;

class BookService {
    protected $bookModel;

    public function __construct(Book $bookModel) {
        $this->bookModel = $bookModel;
    }

    public function validateBook($book): void {
        SessionHelper::setFlash('previousBookInput', $book);

        Validator::validate($book['ISBN'], 'ISBN', [ 'required', 'numeric' ]);
        Validator::validate($book['author'], 'Author', [ 'required' ]);
        Validator::validate($book['title'], 'Title', [ 'required' ]);
        Validator::validate($book['quantity'], 'Quantity', [ 'numeric' ]);

        if(Validator::hasErrors()) throw new ValidationException(Validator::getErrors());
    }

    public function insertBook($book): bool {
        $result = $this->bookModel->addBook($book);

        if($result) SessionHelper::unsetFlash('previousBookInput');

        return $result;
    }
}