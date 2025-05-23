<?php
require_once CONTROLLER . "apiController.tpl";

/**
 * ApiRouting
 * 
 * Handles the routing for all API requests.
 */
class ApiRouting {

    /**
     * Constructor
     * 
     * Parses the API request URL and dispatches to the appropriate resource controller.
     */
    public function __construct() {
        // --- Start Authentication Check ---
        // ApiController is already included at the top of the file.
        $authApiController = new ApiController(); // For auth errors

        $suppliedApiKey = $_SERVER['HTTP_X_API_KEY'] ?? null;

        if ($suppliedApiKey === null) {
            $authApiController->sendError("API Key is missing", 401); // sendError also exits
        }

        if (!defined('VALID_API_KEYS') || !is_array(VALID_API_KEYS)) {
            // This case is a server misconfiguration, should not happen in normal operation
            error_log("ApiRouting Error: VALID_API_KEYS is not defined or not an array in config.tpl.");
            $authApiController->sendError("Server configuration error", 500);
        }

        if (!in_array($suppliedApiKey, VALID_API_KEYS)) {
            $authApiController->sendError("Invalid API Key", 401); // sendError also exits
        }
        // --- End Authentication Check; if we are here, key is valid ---

        // Existing URL parsing and routing logic starts here
        $url = (isset($_GET['url'])) ? $_GET['url'] : false;
        
        // If no URL is provided after /api/, it's an error (caught by resource check later)
        // Example: /api/ or /api
        // This check is slightly different from before but achieves a similar goal.
        // The previous check for !$url is now effectively handled by the resource check.
        // If $_GET['url'] is 'api' or 'api/', $urlParts will have 'api' as first element.

        $url = rtrim($url, "/");
        $urlParts = explode("/", $url); // e.g., api/accounts/123

        // The general purpose $apiGeneralErrorHandler instance for routing errors
        $apiGeneralErrorHandler = new ApiController(); 

        // We expect the URL to be like "api/resource/id"
        // $urlParts[0] should be "api"
        // $urlParts[1] should be the resource
        // $urlParts[2] (optional) should be the id

        // Check if the URL starts with 'api' and has at least a resource part
        if (count($urlParts) < 2 || $urlParts[0] !== 'api') {
            // This could happen if index.php logic changes or if called directly with malformed URL
            $apiGeneralErrorHandler->sendError("Invalid API request format. Expecting /api/resource[/id]", 400);
            return;
        }
        
        $resource = $urlParts[1] ?? null; // This will be null if URL is just 'api'
        $id = $urlParts[2] ?? null;

        if (!$resource) { // Handles cases like /api/ or /api (where $urlParts[1] is not set)
            $apiGeneralErrorHandler->sendError("API resource not specified", 400);
            return;
        }

        // Normalize resource name for file and class lookup (e.g., all lowercase)
        $normalizedResource = strtolower($resource);
        $resourceControllerPath = CONTROLLER . "api/" . $normalizedResource . "ApiController.tpl";
        
        if (file_exists($resourceControllerPath)) {
            require_once $resourceControllerPath;
            // Class name convention: e.g., 'accountsApiController'
            $resourceControllerName = $normalizedResource . "ApiController";
            if (class_exists($resourceControllerName)) {
                $resourceController = new $resourceControllerName();
                // Pass the HTTP method, ID, and any request data (e.g., from POST)
                $resourceController->handleRequest($_SERVER['REQUEST_METHOD'], $id, $_REQUEST);
            } else {
                $apiGeneralErrorHandler->sendError("API endpoint controller class not found: " . $resourceControllerName, 500);
            }
        } else {
            $apiGeneralErrorHandler->sendError("API endpoint not found: " . $resource, 404);
        }
    }
}
?>
