<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Report;

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

    public function generate() {
        $data = json_decode(file_get_contents('php://input'), true);
        $type = $data['type'] ?? '';
        $from = $data['from'] ?? null;
        $to = $data['to'] ?? null;

        $reportData = [];

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
                echo json_encode(['data' => [], 'error' => 'Invalid report type']);
                return;
        }

        echo json_encode(['data' => $reportData]);
    }
}
