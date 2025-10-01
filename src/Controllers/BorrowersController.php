<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Validator;
use App\Models\Borrower;
use Helpers\RedirectHelper;
use App\Core\ValidationException;
use Helpers\SessionHelper;
use SessionHandler;
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
                "fname" => filter_var($_POST['fname'], FILTER_SANITIZE_STRING),
                "lname" => filter_var($_POST['lname'], FILTER_SANITIZE_STRING),
                "email" => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                "phone" => filter_var($_POST['phone'], FILTER_SANITIZE_STRING),
                "address" => filter_var($_POST['address'], FILTER_SANITIZE_STRING),
                "birth" => filter_var($_POST['birth'], FILTER_SANITIZE_STRING)
            ];

            SessionHelper::setFlash('previousBorrowerInput', $borrower);

            $errors = [];

            if($error = Validator::required($borrower['fname'], 'First Name')) $errors[] = $error;
            if($error = Validator::required($borrower['lname'], 'Last Name')) $errors[] = $error;

            if($error = Validator::required($borrower['email'], 'Email')) $errors[] = $error;
            else if($error = Validator::email($borrower['email'])) $errors[] = $error;

            if(!empty($borrower['phone']) && $error = Validator::numeric($borrower['phone'], 'Phone Number')) $errors[] = $error;


            if($errors) throw new ValidationException($errors);

            $result = $this->borrowerModel->addBorrower($borrower);

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

        $this->view("/user/borrowers/edit", [ 
            "title" => "Edit Borrower", 
            'borrower' => $borrower
        ]);
    }

    public function update() {

    }

    public function delete() {

    }
}
