<?php

namespace Helpers;

use Helpers\SessionHelper;

class RedirectHelper {
    public static function withFlash($type, $message, $route): void {
        SessionHelper::setFlash($type, $message);
        header("Location: " . routeTo($route));
        exit;
    }
}