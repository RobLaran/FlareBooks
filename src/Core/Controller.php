<?php
namespace App\Core;

class Controller {
    protected $title;
    protected $page;
    protected $limit;
    protected $sortBy;
    protected $sortDir;
    protected $search;

    protected function view($view, $data = []) {
        extract($data);
        
        ob_start();

        require "src/Views/pages/$view.php";

        $content = ob_get_clean();

        require 'src/Views/layout.php';
    }

    protected function getRequestParams(array $defaults = []): array {
        return [
            "page" => isset($_GET['page']) ? (int)$_GET['page'] : ($defaults['page'] ?? 1),
            "limit" => $_GET['limit'] ?? ($defaults['limit'] ?? 5),
            "sortBy" => $_GET['sortBy'] ?? ($defaults['sortBy'] ?? 'id'),
            "sortDir" => $_GET['sortDir'] ?? ($defaults['sortDir'] ?? 'ASC'),
            "search" => trim($_GET['search'] ?? ($defaults['search'] ?? ''))
        ];
    }
}
