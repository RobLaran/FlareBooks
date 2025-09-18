<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Genre;

class GenresController extends Controller {
    private $genreModel;

    public function __construct() {
        $this->title = "Genres";
        $this->genreModel = new Genre();
    }
    public function index(): void {
        $params = $this->getRequestParams();

        if($params['limit'] == 'all') {
            $genres = $this->genreModel->getAllGenres($params['sortBy'], $params['sortDir']);
            $totalGenres = $this->genreModel->getTotalGenres($params['search']);
            $totalPages = 1;
            $params['limit'] = $totalGenres;
        } else {
            $offset = ($params['page'] - 1) * $params['limit'];

            $genres = $this->genreModel->getPaginatedGenres($params['limit'], $offset, $params['sortBy'], $params['sortDir'], $params['search']);

            $totalGenres = $this->genreModel->getTotalGenres($params['search']);
            $totalPages = ceil($totalGenres / $params['limit']);
        }

        $this->view("genres",  array_merge($params , [
            "title" => $this->title,
            "genres" => $genres,
            "totalGenres" => $totalGenres,
            "totalPages" => $totalPages
        ]));
    }
}
