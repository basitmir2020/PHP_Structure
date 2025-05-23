<?php
namespace App\Http;

// require_once CONTROLLER . "apiController.tpl"; // Removed, BaseApiController will be autoloaded

/**
 * ApiRouter
 * 
 * Handles the routing for all API requests.
 */
class ApiRouter { // Renamed from ApiRouting

    /**
     * Constructor
     * 
     * Parses the API request URL and dispatches to the appropriate resource controller.
     */
    public function __construct() {
        // --- Start Authentication Check ---
        $authApiController = new \App\Http\BaseApiController(); // Updated to FQN

        $suppliedApiKey = $_SERVER['HTTP_X_API_KEY'] ?? null;

        if ($suppliedApiKey === null) {
            $authApiController->sendError("API Key is missing", 401); 
        }

        if (!defined('VALID_API_KEYS') || !is_array(VALID_API_KEYS)) {
            error_log("ApiRouter Error: VALID_API_KEYS is not defined or not an array in config.tpl.");
            $authApiController->sendError("Server configuration error", 500);
        }

        if (!in_array($suppliedApiKey, VALID_API_KEYS)) {
            $authApiController->sendError("Invalid API Key", 401); 
        }
        // --- End Authentication Check; if we are here, key is valid ---

        $url = (isset($_GET['url'])) ? $_GET['url'] : false;
        
        $url = rtrim($url, "/");
        $urlParts = explode("/", $url); 

        $apiGeneralErrorHandler = new \App\Http\BaseApiController(); // Updated to FQN

        if (count($urlParts) < 2 || $urlParts[0] !== 'api') {
            $apiGeneralErrorHandler->sendError("Invalid API request format. Expecting /api/resource[/id]", 400);
            return;
        }
        
        $resource = $urlParts[1] ?? null; 
        $id = $urlParts[2] ?? null;

        if (!$resource) { 
            $apiGeneralErrorHandler->sendError("API resource not specified", 400);
            return;
        }

        // New Resource Controller Loading Logic
        $resourceNameNormalized = ucfirst(strtolower($resource)); // e.g., "accounts" -> "Accounts"
        $controllerClassName = "App\\Http\\Api\\" . $resourceNameNormalized . "Controller";

        if (class_exists($controllerClassName)) {
            $resourceController = new $controllerClassName();
            // Assuming handleRequest method exists and is public in the resource controller
            $resourceController->handleRequest($_SERVER['REQUEST_METHOD'], $id, $_REQUEST);
        } else {
            // error_log("ApiRouter Error: Controller class '{$controllerClassName}' not found."); // Autoloader will warn if file not found
            $apiGeneralErrorHandler->sendError("API endpoint controller class not found: " . $controllerClassName, 500);
        }
    }
}
?>
