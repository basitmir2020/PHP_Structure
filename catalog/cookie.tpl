<?php
class CookieManager {

    /**
     * Sets a cookie with secure defaults.
     *
     * @param string $name The name of the cookie.
     * @param string $value The value of the cookie. Default is an empty string.
     * @param int $expires The time the cookie expires. This is a Unix timestamp, so time() + seconds is typical. Default is 0 (session cookie).
     * @param string $path The path on the server in which the cookie will be available on. Default is '/'.
     * @param string $domain The (sub)domain that the cookie is available to. Default is empty string (current host).
     * @param bool|null $secure Indicates that the cookie should only be transmitted over a secure HTTPS connection.
     *                          Defaults to null, which means it will be true if HTTPS is used, false otherwise.
     * @param bool $httpOnly When TRUE the cookie will be made accessible only through the HTTP protocol.
     *                       This means that the cookie won't be accessible by scripting languages, such as JavaScript. Default is true.
     * @param string $sameSite ('Lax', 'Strict', 'None') Asserts that a cookie must not be sent with cross-origin requests. 'Lax' is a good default.
     *                         If 'None', the 'Secure' attribute must also be set.
     */
    public static function set(
        string $name,
        string $value = "",
        int $expires = 0,
        string $path = "/",
        string $domain = "",
        bool $secure = null,
        bool $httpOnly = true,
        string $sameSite = 'Lax'
    ) {
        if ($secure === null) {
            $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
        }

        if (strtolower($sameSite) === 'none' && !$secure) {
            // Warning or error: SameSite=None requires Secure attribute
            // For simplicity here, we won't set the cookie if this condition is violated,
            // or one could default SameSite to 'Lax'.
            // Let's log an error and not set the cookie to highlight the issue.
            error_log("CookieManager Error: Setting a cookie with SameSite=None requires the Secure attribute to be set. Cookie '{$name}' not set.");
            return;
        }
        
        // PHP 7.3+ allows setting SameSite directly in $options array
        if (PHP_VERSION_ID >= 70300) {
            $options = [
                'expires' => $expires,
                'path' => $path,
                'domain' => $domain,
                'secure' => $secure,
                'httponly' => $httpOnly,
                'samesite' => $sameSite,
            ];
            setcookie($name, $value, $options);
        } else {
            // For PHP < 7.3, SameSite must be appended to path if needed.
            // This is a simplified approach; full SameSite support for older PHP is more complex.
            $pathWithSameSite = $path . (empty($sameSite) ? '' : '; samesite=' . $sameSite);
            setcookie($name, $value, $expires, $pathWithSameSite, $domain, $secure, $httpOnly);
        }
    }

    /**
     * Gets a cookie value.
     *
     * @param string $name The name of the cookie.
     * @return string|null The value of the cookie, or null if not found.
     */
    public static function get(string $name) {
        return $_COOKIE[$name] ?? null;
    }

    /**
     * Deletes a cookie.
     *
     * @param string $name The name of the cookie to delete.
     * @param string $path The path of the cookie.
     * @param string $domain The domain of the cookie.
     */
    public static function delete(string $name, string $path = "/", string $domain = "") {
        // Set expiry to one hour in the past
        // Secure and HttpOnly flags should ideally also match the set cookie to ensure deletion.
        // For this simplified version, we just expire it.
        // The 'secure' and 'httponly' parameters for deletion should match how the cookie was set.
        // However, PHP's setcookie for deletion primarily relies on name, path, domain, and past expiry.
        if (isset($_COOKIE[$name])) {
             // To ensure deletion, set value to empty and expiry in the past.
             // Path and domain must match how the cookie was set.
            self::set($name, "", time() - 3600, $path, $domain); // Uses the secure defaults of self::set()
            unset($_COOKIE[$name]); // Also unset from current request's $_COOKIE array
        }
    }
}
?>
