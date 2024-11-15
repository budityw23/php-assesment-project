<?php
namespace Validator;

use Exception\ValidationException;

class Validator {
    public static function validateEmail($email) {
        if (empty($email)) {
            throw new ValidationException("Invalid email format");
        }

        // Remove whitespace
        $email = trim($email);

        // Basic email validation regex
        $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        
        if (!preg_match($pattern, $email)) {
            throw new ValidationException("Invalid email format");
        }

        // Additional check using filter_var
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException("Invalid email format");
        }

        return $email;
    }

    public static function validateId($id) {
        // Convert to string and trim
        $id = trim((string)$id);
        
        // Remove leading zeros
        $id = ltrim($id, '0');
        
        // Check if empty after removing zeros
        if ($id === '') {
            throw new ValidationException("Invalid ID format");
        }

        // Check if it's a valid positive integer
        if (!ctype_digit($id) || (int)$id <= 0) {
            throw new ValidationException("Invalid ID format");
        }

        // Convert to integer
        $validId = (int)$id;

        // Check if within valid range
        if ($validId > PHP_INT_MAX) {
            throw new ValidationException("ID is too large");
        }

        return $validId;
    }
}
