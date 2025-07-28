<?php
// src/Sanitizer.php

interface SanitizerInterface {
    public static function sanitizeString($str);
    public static function sanitizeEmail($email);
    public static function sanitizePassword($password);
    public static function validateRequired($value);
}

class Sanitizer implements SanitizerInterface {
    public static function sanitizeString($str) {
        return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
    }
    public static function sanitizeEmail($email) {
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : '';
    }
    public static function sanitizePassword($password) {
        return trim($password);
    }
    public static function validateRequired($value) {
        return isset($value) && trim($value) !== '';
    }
}
