<?php
namespace App\Http;

use App\Persistence\DbContext;      // Added for DI
use App\Service\AccountService;     // Added for DI
// use App\Interfaces\Service\IAccountService; // Optional, if type hinting AccountService against interface

// BaseApiController is already used via FQN: \App\Http\BaseApiController

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

        // Determine method name (moved from CoreRouter to here for clarity)
        if (isset($urlParts[2]) && !empty($urlParts[2]) && $resourceNameNormalized === ucfirst(strtolower($urlParts[1]))) {
             // If $urlParts[2] exists and is not the ID (i.e. if $urlParts[1] is the resource), then $urlParts[2] could be a method.
             // This logic is a bit simplified. A more robust router would clearly distinguish methods from IDs.
             // For now, assume if $urlParts[2] is present, it might be a method if $urlParts[1] is the resource.
             // But AccountsController uses $id as $urlParts[2], so this section needs careful thought.
             // The original CoreRouter passed $url[1] as the method if $url[0] was controller.
             // Here, $urlParts[0] is 'api', $urlParts[1] is resource.
             // The AccountsController expects $id at $urlParts[2].
             // So, we will rely on a default method for now for simplicity, or a method specifically called.
             // Let's assume a default 'handleRequest' or specific methods defined in controllers.
             // The prompt for AccountsController implies handleRequest is the entry point.
        }
        // $methodName will be handled by the controller's handleRequest method itself.
        // $arguments are also passed to handleRequest which then distributes them.
        // The main task here is instantiating the controller with its dependencies.

        if (class_exists($controllerClassName)) {
            $controllerInstance = null;
            // Dependency Injection for specific controllers
            if ($controllerClassName === "App\\Http\\Api\\AccountsController") {
                $dbContext = new DbContext(); 
                $accountService = new AccountService($dbContext); 
                // If using interface for type hinting:
                // IAccountService $accountService = new AccountService($dbContext);
                $controllerInstance = new $controllerClassName($accountService); // Inject service
            } else {
                // Fallback for other controllers (will fail if they have constructor dependencies)
                // This part needs a more robust DI strategy for a real application.
                // For now, this handles the specific case of AccountsController.
                try {
                    $controllerInstance = new $controllerClassName();
                } catch (\Error $e) { // Catch constructor argument errors
                    error_log("ApiRouter Error: Failed to instantiate '{$controllerClassName}' without arguments. Error: " . $e->getMessage());
                    $controllerInstance = null; // Ensure it's null if instantiation failed
                }
            }
            
            if ($controllerInstance && method_exists($controllerInstance, 'handleRequest')) { // Assuming 'handleRequest'
                // Pass method, id, and requestData to handleRequest
                $controllerInstance->handleRequest($_SERVER['REQUEST_METHOD'], $id, $_REQUEST);
            } else {
                // Method not found or controller couldn't be instantiated as expected
                error_log("ApiRouter Error: Method 'handleRequest' not found in controller '{$controllerClassName}' or controller instantiation failed.");
                $apiGeneralErrorHandler->sendError("API endpoint method not found or controller error", 500); 
            }
        } else {
            // Controller class not found ... (existing error handling)
            error_log("ApiRouter Error: Controller class '{$controllerClassName}' not found.");
            $apiGeneralErrorHandler->sendError("API endpoint controller class not found: " . $controllerClassName, 500);
        }
    }
}
?>
