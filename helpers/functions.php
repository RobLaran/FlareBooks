<?php
function getURI(): array|string {
    return str_replace(BASE_URL, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
}

function getFile($path=""): string {
    return BASE_URL . "/" . $path;
}

 function isURL(string $URL): bool {
     $PATH = getURI();
     
    return  $PATH == $URL;
}

function isImageUrl(string $image): bool {
    if(empty($image)) return false;

    return filter_var($image, FILTER_VALIDATE_URL) !== false;
}

function formatImage(string $image): string {
    if (!empty($image) && !isImageUrl($image)) {
        return getFile('public/img/') . $image;
    } 

    return $image;
}

function formatDate(string $date): string {
    return date("F j, Y", strtotime($date));
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
