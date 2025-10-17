<?php

namespace App\Middleware;

use Helpers\RedirectHelper;

class AuthMiddleware {
    public static function check() {
        if(!isset($_SESSION['user']['id'])) {
            http_response_code(401);
            RedirectHelper::withFlash('error', 'Forbidden. You must logged in.', '/auth/login');
        }
    }

    public static function checkRole($allowedRoles=[]) {
        self::check();

        if (!in_array($_SESSION['user']['role'], $allowedRoles)) {
            http_response_code(403);
            echo "Access denied.";
            exit;
        }
    }
}