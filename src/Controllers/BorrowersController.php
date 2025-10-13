<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Sanitizer;
use App\Models\Borrower;
use Exception;
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
        $borrowers = $this->borrowerModel->getAllBorrowers();

        $this->view("user/borrowers/index", [
            "title" => $this->title,
            "borrowers" => $borrowers
        ]);
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
        } catch (Exception $error) {
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

    public function update($id) {
        try {
            $updatedBorrower = [
                "fname" => Sanitizer::clean($_POST['fname']),
                "lname" => Sanitizer::clean($_POST['lname']),
                "email" => Sanitizer::clean($_POST['email'], 'email'),
                "phone" => Sanitizer::clean($_POST['phone']),
                "address" => Sanitizer::clean($_POST['address']),
                "birth" => Sanitizer::clean($_POST['birth']),
                "status" => Sanitizer::clean($_POST['status'])
            ];

            $this->borrowerService->validateBorrower($updatedBorrower);
            $result = $this->borrowerModel->updateBorrower($id, $updatedBorrower);

            if(!$result) {
                RedirectHelper::withFlash('error', 'Failed to update borrower', '/borrowers/edit/' . $id);
            }

            RedirectHelper::withFlash('success', 'Borrower successfully updated', '/borrowers');
        } catch(ValidationException $errors) {
            RedirectHelper::withFlash('errors', $errors->getErrors(), '/borrowers/edit/' . $id);
        } catch(Exception $error) {
            RedirectHelper::withFlash('error', $error->getMessage(), '/borrowers/edit/' . $id);
        }
    }

    public function delete($id) {
        $result = $this->borrowerModel->removeBorrower($id);

        if(!$result) {
            RedirectHelper::withFlash('error', 'Failed to remove borrower', '/borrowers');
        }

        RedirectHelper::withFlash('success', 'Borrower successfully removed', '/borrowers');
    }

    public function search() {
        $query = $_GET['q'] ?? '';

        $borrowers = $this->borrowerModel->searchBorrowers($query);

        header('Content-Type: application/json');
        echo json_encode($borrowers);
        exit;
    }
}
