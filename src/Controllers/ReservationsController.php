<?php
namespace App\Controllers;

use App\Core\Controller;

class ReservationsController extends Controller {

    public function __construct() {
        $this->title = "Reservations";
    }
    public function index() {
        $this->view("reservations", [ "title" => $this->title ]);
    }
}
