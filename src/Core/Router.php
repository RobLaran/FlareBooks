<?php
namespace App\Core;

class Router {
    private static $routes = [];

    public static function get($path, $action, $middleware = []) {
        self::$routes['GET'][$path] = ['action' => $action, 'middleware' => $middleware];
    }

    public static function post($path, $action, $middleware = []) {
        self::$routes['POST'][$path] = ['action' => $action, 'middleware' => $middleware];
    }

    public static function dispatch($method, $uri) {
        $uri = strtok($uri, '?'); // strip query params

        // Remove base folder (/library) if project is not in root
        $scriptName = dirname($_SERVER['SCRIPT_NAME']); 
        $basePath = rtrim($scriptName, '/');
        if ($basePath && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        if ($uri === '') {
            $uri = '/';
        }

        $routes = self::$routes[$method] ?? [];

        foreach ($routes as $route => $data) {
            $action = $data['action'];
            $middleware = $data['middleware'] ?? [];

            // convert {id} → named group (?P<id>[^/]+)
            $pattern = "@^" . preg_replace('/{([^}]+)}/', '(?P<\1>[^/]+)', $route) . "$@";

            if (preg_match($pattern, $uri, $matches)) {
                // keep only named params
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // run middleware if any
                foreach ($middleware as $mw) {
                    $mwResult = call_user_func($mw, $params);
                    if ($mwResult === false) {
                        return; // stop if middleware fails
                    }
                }

                return self::callAction($action, $params);
            }
        }

        self::render404();
    }

    private static function callAction($action, $params) {
        [$controller, $method] = explode('@', $action);
        $controllerClass = "App\\Controllers\\$controller";
        $controllerObj = new $controllerClass;
        call_user_func_array([$controllerObj, $method], $params);
    }

    private static function render404() {
        http_response_code(404);
        $errorPage = __DIR__ . '/../views/errors/404.php';
        if (file_exists($errorPage)) {
            require $errorPage;
        } else {
            echo "<h1>404 Not Found</h1>";
        }
    }
}
