<?php
namespace App\Config;



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
        if (file_exists(dirname(__DIR__, 2) . '/catalog/functions.php')) {
            require_once dirname(__DIR__, 2) . '/catalog/functions.php';
        }

        // Load Configuration
        // Note: Config.php should be created by setup.php from ConfigTemplate.php
        $configPath = dirname(__DIR__) . '/Config/Config.php';
        if (!file_exists($configPath) && file_exists(dirname(__DIR__) . '/Config/ConfigTemplate.php')) {
            // Just specific for first run if setup.php wasn't run, though setup.php is recommended.
            // We won't auto-copy here to avoid permission issues, but we might warn or just fail gracefully.
        }

        // Start Session
        if (session_status() === PHP_SESSION_NONE) {
            \App\Util\SessionManager::start();
        }

        // Set Timezone
        date_default_timezone_set("Asia/Kolkata");

        // Define Constants
        self::defineConstants();

        // Apply Security Headers
        self::applySecurityHeaders();
    }

    private static function defineConstants()
    {
        // Check if Config class exists, avoiding error if setup hasn't run
        if (!class_exists('App\Config\Config')) {
            return;
        }

        define("WEBPATH", \App\Config\Config::APP_URL . '/');
        // Define other path constants
        define("ROOT", dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
        define("VIEW", "view/");
        define("INC", "includes/");
        define("IMAGES", WEBPATH . "img/");
        define("ADMIN_PATH", WEBPATH . 'admin/');
    }

    private static function applySecurityHeaders()
    {
        header("X-Content-Type-Options: nosniff");
        header("X-Frame-Options: SAMEORIGIN");
        header("Referrer-Policy: strict-origin-when-cross-origin");

        // CORS Setup
        if (!class_exists('App\Config\Config')) {
            return;
        }

        if (isset($_SERVER['HTTP_ORIGIN'])) {
            $allowedOrigins = \App\Config\Config::CORS_ALLOWED_ORIGINS;
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
}
