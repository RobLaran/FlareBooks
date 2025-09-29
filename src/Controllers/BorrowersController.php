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

        $totalItems = $this->borrowerModel->getTotalBorrowers($params['search']);

        if($params['limit'] == 'all') {
            $borrowers = $this->borrowerModel->getAllBorrowers($params['sortBy'], $params['sortDir']);
            $totalPages = 1;
            $params['limit'] = $totalItems;
        } else {
            $offset = ($params['page'] - 1) * $params['limit'];

            $borrowers = $this->borrowerModel->getPaginatedBorrowers($params['limit'], $offset, $params['sortBy'], $params['sortDir'], $params['search']);
            $totalPages = ceil($totalItems / $params['limit']);
        }

        $columns = [
            ["field" => "borrower_code", "name" => "Code", "sortable" => false],
            ["field" => "first_name", "name" => "First Name", "sortable" => true],
            ["field" => "last_name", "name" => "Last Name", "sortable" => true],
            ["field" => "email", "name" => "Email", "sortable" => true],
            ["field" => "phone", "name" => "Phone", "sortable" => true],
            ["field" => "address", "name" => "Address", "sortable" => true],
            ["field" => "date_of_birth", "name" => "Date of Birth", "sortable" => true],
            ["field" => "membership_date", "name" => "Membership Date", "sortable" => true],
            ["field" => "status", "name" => "Status", "sortable" => true],
            ["field" => "actions", "name" => "Actions", "sortable" => false],
        ];

        $this->view("borrowers", array_merge($params , [
            "title" => $this->title,
            "items" => $borrowers,
            "totalItems" => $totalItems,
            "totalPages" => $totalPages,
            'columns' => $columns,
            'emptyNotif' => "No Borrowers Added",
        ]));
    }
}
