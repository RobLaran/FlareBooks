<?php

namespace Helpers;

class SessionHelper {
    public static function setFlash($key, $value) {
        $_SESSION['flash'][$key] = $value;
    }

    public static function unsetFlash($key) {
        unset($_SESSION['flash'][$key]);
    }

    public static function getFlash($key) {
        if (isset($_SESSION['flash'][$key])) {
            $value = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]); 
            return $value;
        }
        return null;
    }

    public static function hasKey($key) {
        return isset($_SESSION['flash'][$key]);
    }
}
