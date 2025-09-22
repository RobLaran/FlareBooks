<?php
use App\Core\Router;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/helpers/functions.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/routes/web.php';

Router::dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
