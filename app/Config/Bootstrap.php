<?php
namespace App\Config;

use Dotenv\Dotenv;

class Bootstrap
{
    public static function init()
    {
        // Fallback Autoloader (in case Composer is not run yet)
        spl_autoload_register(function ($class) {
            $prefix = 'App\\';
            $base_dir = dirname(__DIR__) . '/';
            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                return;
            }
            $relative_class = substr($class, $len);
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
            if (file_exists($file)) {
                require $file;
            }
        });

        // Load Composer Autoloader if available
        if (file_exists(dirname(__DIR__, 2) . '/vendor/autoload.php')) {
            require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
        }

        // Load Catalog Functions (Legacy)
        if (file_exists(dirname(__DIR__, 2) . '/catalog/functions.tpl')) {
            require_once dirname(__DIR__, 2) . '/catalog/functions.tpl';
        }

        // Load Environment Variables
        if (class_exists('Dotenv\Dotenv')) {
            $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
            $dotenv->safeLoad(); // Use safeLoad to avoid error if .env is missing
        } else {
            // Manual .env loading if Dotenv is missing (basic fallback)
            self::loadEnvManually();
        }


        // Start Session
        if (session_status() === PHP_SESSION_NONE) {
            \App\Util\SessionManager::start();
        }

        // Set Timezone
        date_default_timezone_set("Asia/Kolkata");

        // Define Constants (Legacy support, transition to Env/Config)
        self::defineConstants();

        // Apply Security Headers
        self::applySecurityHeaders();
    }

    private static function defineConstants()
    {
        define("WEBPATH", $_ENV['APP_URL'] . '/');
        // Define other path constants if needed by legacy code
        // Ideally, these should be removed in favor of relative paths or config classes
        // But keeping them for now to ensure compatibility with existing templates/controllers
        define("ROOT", dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
        define("VIEW", "view/");
        define("INC", "includes/");
        define("IMAGES", WEBPATH . "img/");
        define("ADMIN_PATH", WEBPATH . 'admin/'); // Assuming Admin module url
    }

    private static function applySecurityHeaders()
    {
        header("X-Content-Type-Options: nosniff");
        header("X-Frame-Options: SAMEORIGIN");
        header("Referrer-Policy: strict-origin-when-cross-origin");

        // CORS Setup
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            $allowedOrigins = explode(',', $_ENV['CORS_ALLOWED_ORIGINS'] ?? '');
            if (in_array('*', $allowedOrigins) || in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
                header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
                header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
                header('Access-Control-Allow-Headers: X-API-KEY, Content-Type');
                header('Access-Control-Max-Age: 86400');    // cache for 1 day
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
            }
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            }
            exit(0);
        }
    }

    private static function loadEnvManually()
    {
        $path = dirname(__DIR__, 2) . '/.env';
        if (!file_exists($path)) {
            return;
        }
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0)
                continue;
            list($name, $value) = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value);
        }
    }
}
