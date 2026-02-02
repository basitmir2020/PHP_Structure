<?php
namespace App\Util;

class Security
{
    /**
     * Escape output to prevent XSS.
     * Use this when outputting user-generated content in HTML.
     *
     * @param string $string The string to escape.
     * @return string The escaped string.
     */
    public static function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Generate a CSRF token and store it in the session.
     * Call this before rendering a form.
     *
     * @return string The generated token.
     */
    public static function generateCSRFToken()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validate a CSRF token from a form submission.
     *
     * @param string $token The token submitted with the form.
     * @return bool True if valid, false otherwise.
     */
    public static function validateCSRFToken($token)
    {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Sanitize input based on type.
     *
     * @param mixed $input The input data.
     * @param string $type The expected type (string, email, int, float, url).
     * @return mixed The sanitized data.
     */
    public static function sanitize($input, $type = 'string')
    {
        switch ($type) {
            case 'email':
                return filter_var($input, FILTER_SANITIZE_EMAIL);
            case 'int':
                return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
            case 'float':
                return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            case 'url':
                return filter_var($input, FILTER_SANITIZE_URL);
            case 'string':
            default:
                // Strip tags and trim
                return trim(strip_tags($input));
        }
    }
}
