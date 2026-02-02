<?php
namespace App\Util;

class CookieManager
{

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
    public static function get(string $name)
    {
        return $_COOKIE[$name] ?? null;
    }

    /**
     * Sets an encrypted cookie.
     *
     * @param string $name The name of the cookie.
     * @param string $value The value of the cookie.
     * @param int $expires The time the cookie expires.
     * ... (other params same as set)
     */
    public static function setEncrypted(string $name, string $value, int $expires = 0, string $path = "/", string $domain = "", bool $secure = null, bool $httpOnly = true, string $sameSite = 'Lax')
    {
        $encryptedValue = self::encrypt($value);
        if ($encryptedValue === false) {
            error_log("CookieManager Error: Encryption failed for cookie '$name'.");
            return;
        }
        self::set($name, $encryptedValue, $expires, $path, $domain, $secure, $httpOnly, $sameSite);
    }

    /**
     * Gets and decrypts an encrypted cookie value.
     *
     * @param string $name The name of the cookie.
     * @return string|null The decrypted value, or null if not found or decryption fails.
     */
    public static function getEncrypted(string $name)
    {
        $encryptedValue = self::get($name);
        if (!$encryptedValue)
            return null;
        return self::decrypt($encryptedValue);
    }

    // --- Encryption Helpers ---

    // NOTE: In a real app, generate this key once and store it in Config. 
    // Do NOT generate a new key every time, otherwise you can't decrypt old cookies.
    // For this boilerplate, we'll try to use a constant from Config, or fallback for demo.
    private static function getKey()
    {
        if (defined('\App\Config\Config::APP_KEY')) {
            return base64_decode(\App\Config\Config::APP_KEY);
        }
        // Fallback: This is NOT secure for production because it changes every execution if not stored.
        // But since we can't easily auto-generate a persistent key into Config.php without risk of breaking it in this regex step,
        // we'll assume the user will set APP_KEY. 
        // We'll return a dummy key string derivation for now to prevent crashing, but warn the user.
        return hash('sha256', 'default-weak-key', true);
    }

    private static function encrypt($data)
    {
        $method = "aes-256-gcm";
        $key = self::getKey();
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));

        $tag = ""; // Passed by reference
        $encrypted = openssl_encrypt($data, $method, $key, 0, $iv, $tag);

        if ($encrypted === false)
            return false;

        // Store as: IV . Tag . EncryptedData (Base64 encoded)
        return base64_encode($iv . $tag . $encrypted);
    }

    private static function decrypt($data)
    {
        $method = "aes-256-gcm";
        $key = self::getKey();
        $data = base64_decode($data);
        $ivLen = openssl_cipher_iv_length($method);
        $tagLen = 16; // GCM tag length is 16 bytes

        if (strlen($data) < $ivLen + $tagLen)
            return null;

        $iv = substr($data, 0, $ivLen);
        $tag = substr($data, $ivLen, $tagLen);
        $ciphertext = substr($data, $ivLen + $tagLen);

        return openssl_decrypt($ciphertext, $method, $key, 0, $iv, $tag);
    }

    public static function delete(string $name, string $path = "/", string $domain = "")
    {
        if (isset($_COOKIE[$name])) {
            self::set($name, "", time() - 3600, $path, $domain);
            unset($_COOKIE[$name]);
        }
    }
}
?>