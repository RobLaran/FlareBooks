<?php

namespace Helpers;

class SessionHelper {
    public static function setFlash($key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function getFlash($key) {
        if (isset($_SESSION['flash'][$key])) {
            $value = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]); 
            return $value;
        }
        return null;
    }
}
