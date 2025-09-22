<?php
function getURI(): array|string {
    return str_replace(BASE_URL, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
}

function getFile($path=""): string {
    return BASE_URL . "/" . $path;
}

 function isURL($URL): bool {
    $PATH = getURI();

    return  $PATH == $URL || str_contains($PATH, $URL);
}

function isImageUrl(string $image): bool {
    return filter_var($image, FILTER_VALIDATE_URL) !== false;
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
