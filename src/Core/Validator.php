<?php 

namespace App\Core;

class Validator {
    private static $errors = [];

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

    public static function validate($value, $fieldName="", $options=[], $other='') {
        foreach ($options as $option) {
            switch ($option) {
                case 'required':
                    if($error = static::required($value, $fieldName)) {
                         static::$errors[] = $error;
                         return;
                    }
                    break;

                case 'email':
                    if($error = static::email($value)) {
                         static::$errors[] = $error;
                         return;
                    }
                    break;

                case 'date':
                    if($error = static::date($value, $fieldName)) {
                         static::$errors[] = $error;
                         return;
                    }
                    break;

                case 'match':
                    if($error = static::match($value, $other, $fieldName)) {
                         static::$errors[] = $error;
                         return;
                    }
                    break;
                
                case 'numeric':
                    if($error = static::numeric($value, $fieldName)) {
                         static::$errors[] = $error;
                         return;
                    }
                    break;
                
                default:
                    # code...
                    break;
            }
        }
    }

    public static function hasErrors() { return !empty(static::$errors); }

    public static function getErrors() { return static::$errors; }
}