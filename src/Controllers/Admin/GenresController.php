<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Sanitizer;
use App\Core\ValidationException;
use App\Core\Validator;
use App\Models\Genre;
use Exception;
use Helpers\RedirectHelper;

class GenresController extends Controller {
    private $genreModel;

    public function __construct() {
        $this->title = "Genres";
        $this->genreModel = new Genre();
    }
    public function index(): void {
        $genres = $this->genreModel->getAllGenres();

        $this->view("/admin/genres",  [
            "title" => $this->title,
            "genres" => $genres
        ]);
    }

    public function add() {
        try {
            $genre = [
                "name" => Sanitizer::clean($_POST['name']),
                "description" => Sanitizer::clean($_POST['description'])
            ];

            Validator::validate($genre['name'], 'Genre name', ['required']);

            if(Validator::hasErrors()) throw new ValidationException(Validator::getErrors());

            if($this->genreModel->genreExist($genre['name'])) throw new Exception('Genre name already exist');

            $result = $this->genreModel->addGenre($genre);

            if(!$result) {
                RedirectHelper::withFlash('error', 'Failed to add genre', '/admin/genres');
            }
            
            RedirectHelper::withFlash('success', 'New genre successfully added', '/admin/genres');
        } catch(ValidationException $errors) {
            RedirectHelper::withFlash('errors', $errors->getErrors(), '/admin/genres');
        } catch(Exception $error) {
            RedirectHelper::withFlash('error', $error->getMessage(), '/admin/genres');
        }
    }
}
