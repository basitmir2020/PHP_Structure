<?php
    define("WEBPATH","http://localhost/PHP_Structure/");
    define("ADMIN_PATH",WEBPATH.'Admin/');
    define('SESSIONTIME',$_SERVER['REQUEST_TIME']);
    define('TIMEOUT',3600);
    include('catalog/session.tpl'); // Should this be require_once? For now, keeping as include.
    session::start();
    error_reporting(E_ALL);
    // For production APIs, consider setting ini_set('display_errors', '0');
    // and implementing robust logging. The ApiController is designed to send
    // JSON errors, but this would prevent raw PHP errors from being output.

    date_default_timezone_set("Asia/Kolkata");

    // Security Headers
    // Set X-Content-Type-Options to prevent MIME-sniffing
    header("X-Content-Type-Options: nosniff");

    // Set X-Frame-Options to protect against clickjacking
    // Use 'SAMEORIGIN' to allow framing by the site itself, or 'DENY' to prevent all framing
    header("X-Frame-Options: SAMEORIGIN");

    // Set Referrer-Policy for better control over referrer information
    header("Referrer-Policy: strict-origin-when-cross-origin");

    // Content Security Policy (CSP) - A starting point
    // This policy allows resources from 'self' (same origin), inline scripts/styles, 
    // and data URIs for images/fonts. It blocks plugins and restricts framing to 'self'.
    // For a production environment, aim to remove 'unsafe-inline' for scripts and styles
    // by refactoring inline event handlers and styles.
    $cspDirectives = [
        "default-src 'self'",
        "script-src 'self' 'unsafe-inline'", // Allows inline JS and scripts from the same origin
        "style-src 'self' 'unsafe-inline'",  // Allows inline CSS and styles from the same origin
        "img-src 'self' data:",              // Allows images from the same origin and data URIs
        "font-src 'self' data:",               // Allows fonts from the same origin and data URIs
        "object-src 'none'",                 // Disallows embedding objects like Flash, Java applets
        "frame-ancestors 'self'",            // Similar to X-Frame-Options: SAMEORIGIN, controls embedding
        // "form-action 'self'",             // Optional: Restricts where forms can submit to
        // "base-uri 'self'",                // Optional: Restricts what can be used in <base> tags
        // "report-uri /csp-violation-report-endpoint.php;", // Optional: For browsers to report CSP violations
    ];
    header("Content-Security-Policy: " . implode('; ', $cspDirectives));

    // HTTP Strict Transport Security (HSTS)
    // IMPORTANT: ONLY uncomment and enable HSTS if your entire site is consistently served over HTTPS
    // and you understand the implications. If misconfigured, your site could become inaccessible.
    // Start with a short max-age for testing, e.g., max-age=60 (1 minute), then gradually increase.
    // A common production value is max-age=31536000 (1 year).
    // // header("Strict-Transport-Security: max-age=60;"); // Example: 1 minute for testing
    // // header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload"); // Production example

    //SERVER
    define("CATALOG","catalog/");
    define("CONTROLLER","controller/");
    define("IMAGES",WEBPATH."img/");
    define("INC","includes/");
    define("MODEL","model/");
    define("DBASE","persistence/");
    define("PROCESS","process/");
    define("SCRIPTS",WEBPATH."js/");
    define("STYLES",WEBPATH."css/");
    define("LIB","library/");
    define("ROOT",dirname(__FILE__)."\\"); // Consider using / for cross-platform compatibility if issues arise
    define("UPLOAD_PATH",ROOT."img\\uploads\\"); // Same as above for path separator
    define("VIEW","view/");
    define("VENDOR",WEBPATH.'vendor/');
    define("FONTS",WEBPATH."fonts/");

    // API Specific
    define("API_BASE_PATH", "/api"); // Base path for all API routes
    define("API_VERSION", "v1");     // Current API version
    // API Authentication
    define('VALID_API_KEYS', ['your-super-secret-api-key-12345']); // Add your desired secret key here

    //LOGOUT ROUTES
    define('USER_LOGOUT',WEBPATH."login".'?RID=103');
    define('ADMIN_LOGOUT',ADMIN_PATH.'?RID=103');

    //REQUEST ROUTES
    

    //MESSAGES
    define('LOGOUT_MESSAGE','Successfully Logged Out!');
    define('LOGIN_MESSAGE','Successfully Logged In!');


    require_once VIEW. "CoreEssentials.tpl"; // Replaced htmlEssentials with CoreEssentials
    require_once CATALOG.'validation.tpl';
    require_once CATALOG."Validator.tpl"; // Added Validator
    require_once CATALOG."cookie.tpl"; // Added CookieManager
    require_once DBASE.'dbContext.tpl';
    require_once CATALOG.'functions.tpl';

    //Database Configuration
    define('DBHOST','localhost');
    define('DBUSER','root');
    define('DBPASS','');
    define('DBNAME',''); // Database name should be configured for a working application
?>
