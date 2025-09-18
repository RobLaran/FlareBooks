<?php
function getURI(): array|string {
    return str_replace(BASE_URL, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
}

 function isURL($URL): bool {
    $PATH = getURI();

    return  $PATH == $URL;
}

function routeTo($endPoint): string {
    $basePath = BASE_URL;
    return $basePath . $endPoint;
}

function dd($var) {
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
    die();
}
