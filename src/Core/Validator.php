<?php 

namespace App\Core;

class Validator {
public static function required($value, $fieldName) {
        if (empty(trim($value))) {
            return "$fieldName is required.";
        }
        return null;
    }

    public static function email($value) {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return "$value is not a valid email.";
        }
        return null;
    }

    public static function minLength($value, $length, $fieldName) {
        if (strlen($value) < $length) {
            return "$fieldName must be at least $length characters long.";
        }
        return null;
    }

    public static function maxLength($value, $length, $fieldName) {
        if (strlen($value) > $length) {
            return "$fieldName must not exceed $length characters.";
        }
        return null;
    }

    public static function date($value, $fieldName) {
        if (!\DateTime::createFromFormat('Y-m-d', $value)) {
            return "$fieldName is not a valid date (expected format: YYYY-MM-DD).";
        }
        return null;
    }

    public static function match($value, $other, $fieldName) {
        if ($value !== $other) {
            return "$fieldName does not match.";
        }
        return null;
    }

    public static function numeric($value, $fieldName) {
        if (!preg_match('/^[0-9]+$/', $value)) {
            return "$fieldName must be a valid number.";
        }
        return null;
    }
}