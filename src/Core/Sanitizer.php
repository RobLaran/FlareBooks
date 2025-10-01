<?php

namespace App\Core;

class Sanitizer {
    public static function clean($value, $type='string') {
        $cleaned = htmlspecialchars(trim($value));

        switch ($type) {
            case 'string':
                return filter_var($cleaned, FILTER_SANITIZE_STRING);

            case 'email':
                return filter_var($cleaned, FILTER_SANITIZE_EMAIL);

            default:
                # code...
                break;
        }
    }
}