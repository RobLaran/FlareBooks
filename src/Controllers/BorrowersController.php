<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Sanitizer;
use App\Models\Borrower;
use Helpers\RedirectHelper;
use App\Core\ValidationException;
use Helpers\SessionHelper;
use Services\BorrowerService;

class BorrowersController extends Controller {
    protected $borrowerModel;
    protected $borrowerService;

    public function __construct() {
        $this->title = "Borrowers List";
        $this->borrowerModel = new Borrower();
        $this->borrowerService = new BorrowerService($this->borrowerModel);
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

        $addButton = [
            "route" => "/borrowers/add",
            "label" => "Add Borrower"
        ];

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

        $this->view("user/borrowers/index", array_merge($params , [
            "title" => $this->title,
            "items" => $borrowers,
            "totalItems" => $totalItems,
            "totalPages" => $totalPages,
            'columns' => $columns,
            'emptyNotif' => "No Borrowers Added",
            "addButton" => $addButton
        ]));
    }

    public function create() {
        $old = SessionHelper::getFlash('previousBorrowerInput');

        $this->view("/user/borrowers/add", [ 
            "title" => "Add Borrower", 
            'old' => $old
        ]);
    }

    public function add() {
        try {
            $borrower = [
                "fname" => Sanitizer::clean($_POST['fname']),
                "lname" => Sanitizer::clean($_POST['lname']),
                "email" => Sanitizer::clean($_POST['email'], 'email'),
                "phone" => Sanitizer::clean($_POST['phone']),
                "address" => Sanitizer::clean($_POST['address']),
                "birth" => Sanitizer::clean($_POST['birth'])
            ];

            $this->borrowerService->validateBorrower($borrower);
            $result = $this->borrowerService->registerBorrower($borrower);

            if(!$result) {
                RedirectHelper::withFlash('error', 'Failed to add borrower', '/borrowers/add');
            }
            
            RedirectHelper::withFlash('success', 'New borrower successfully added', '/borrowers');
        } catch(ValidationException $errors) {
            RedirectHelper::withFlash('errors', $errors->getErrors(), '/borrowers/add');
        } catch (\Exception $error) {
            RedirectHelper::withFlash('error', $error->getMessage(), '/borrowers/add');
        }
    }

    public function edit($id) {
        $borrower = $this->borrowerModel->getBorroweById($id);
        $old = SessionHelper::getFlash('previousBorrowerInput');

        $this->view("/user/borrowers/edit", [ 
            "title" => "Edit Borrower", 
            'borrower' => $borrower,
            'old' => $old
        ]);
    }

    public function update() {

    }

    public function delete($id) {
        $result = $this->borrowerModel->removeBorrower($id);

        if(!$result) {
            RedirectHelper::withFlash('error', 'Failed to remove borrower', '/borrowers');
        }

        RedirectHelper::withFlash('success', 'Borrower successfully removed', '/borrowers');
    }
}
