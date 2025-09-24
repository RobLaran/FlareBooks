<?php

namespace App\Middleware;

class AuthMiddleware {
    public static function check() {
        if(!isset($_SESSION['user']['id'])) {
            header('Location: ' . routeTo('/auth/login'));
            exit;
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