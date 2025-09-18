<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Borrower;

class BorrowersController extends Controller {
    protected $borrowerModel;

    public function __construct() {
        $this->title = "Borrowers List";
        $this->borrowerModel = new Borrower();
    }
    public function index() {
        $params = $this->getRequestParams(
            [
                "sortBy" => "borrower_code"
            ]
        );

        if($params['limit'] == 'all') {
            $borrowers = $this->borrowerModel->getAllBorrowers($params['sortBy'], $params['sortDir']);
            $totalBorrowers = $this->borrowerModel->getTotalBorrowers($params['search']);
            $totalPages = 1;
            $params['limit'] = $totalBorrowers;
        } else {
            $offset = ($params['page'] - 1) * $params['limit'];

            $borrowers = $this->borrowerModel->getPaginatedBorrowers($params['limit'], $offset, $params['sortBy'], $params['sortDir'], $params['search']);

            $totalItems = $this->borrowerModel->getTotalBorrowers($params['search']);
            $totalPages = ceil($totalItems / $params['limit']);
        }

        $this->view("borrowers", array_merge($params , [
            "title" => $this->title,
            "array" => $borrowers,
            "totalItems" => $totalItems,
            "totalPages" => $totalPages,
            'tableColumns' => ['Code', 'First Name', 'Last Name', 'Email', 'Phone', 'Address', 'Date of Birth', 'Membership Date', 'Status'],
            'emptyNotif' => "No Borrowers Added",
        ]));
    }
}
