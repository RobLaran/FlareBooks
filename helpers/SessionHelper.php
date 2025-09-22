<?php
class SessionHelper {
    public static function setFlash($key, $message) {
        $_SESSION[$key] = $message;
    }

    public static function getFlash($key) {
        if (isset($_SESSION[$key])) {
            $msg = $_SESSION[$key];
            unset($_SESSION[$key]); 
            return $msg;
        }
        return null;
    }
}
