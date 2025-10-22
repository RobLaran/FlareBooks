<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\ValidationException;
use App\Core\Validator;
use App\Models\Report;
use Exception;
use Helpers\RedirectHelper;

class ReportsController extends Controller
{
    private $reportModel;

    public function __construct() {
        $this->reportModel = new Report();
    }

    public function index() {
        $this->view('/admin/reports', [ 
            "title" => "Reports"
        ], "layout");
    }

    public function stats() {
        try {
            $totals = $this->reportModel->getTotals();
            $recent = $this->reportModel->getRecentBorrowed(8);
            $booksByStatus = $this->reportModel->getBooksByStatus();
            $monthlyBorrowed = $this->reportModel->getMonthlyBorrowed(6);

            echo json_encode([
                'success' => true,
                'totals' => $totals,
                'recent' => $recent,
                'booksByStatus' => $booksByStatus,
                'monthlyBorrowed' => $monthlyBorrowed
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function generate() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $type = $data['type'] ?? '';
            $from = $data['from'] ?? null;
            $to = $data['to'] ?? null;

            if (empty($type)) {
                throw new Exception('Select report type');
            }

            switch ($type) {
                case 'books':
                    $reportData = $this->reportModel->getBooksReport($from, $to);
                    break;
                case 'genres':
                    $reportData = $this->reportModel->getGenresReport();
                    break;
                case 'borrowed':
                    $reportData = $this->reportModel->getBorrowedBooksReport($from, $to);
                    break;
                case 'borrowers':
                    $reportData = $this->reportModel->getBorrowersReport($from, $to);
                    break;
                default:
                    throw new Exception('Invalid report type');
            }

            echo json_encode(['success' => true, 'data' => $reportData]);
        } catch (Exception $error) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $error->getMessage()]);
        }
    }

}
