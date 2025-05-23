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
        $url = (isset($_GET['url'])) ? $_GET['url'] : false;
        if (!$url) {
            // This case should ideally be caught by index.php's check,
            // but as a fallback for direct instantiation or unforeseen scenarios.
            $api = new ApiController();
            $api->sendError("API endpoint not specified", 400);
            return;
        }

        $url = rtrim($url, "/");
        $urlParts = explode("/", $url); // e.g., [api, accounts, 123] or [accounts, 123] if base 'api' is stripped by index.php

        // Assuming index.php ensures $urlParts[0] is 'api' and might strip it.
        // If index.php passes the full path like 'api/accounts/1', then $urlParts[0] is 'api'.
        // The routing logic in the original routing.tpl was:
        // $resource = $url[1] ?? null; $id = $url[2] ?? null;
        // This implies the $url array passed to the old logic was already split.
        // For ApiRouting, we get the full $_GET['url'] again.

        $api = new ApiController();

        // We expect the URL to be like "api/resource/id"
        // $urlParts[0] should be "api"
        // $urlParts[1] should be the resource
        // $urlParts[2] (optional) should be the id

        if (count($urlParts) < 2 || $urlParts[0] !== 'api') {
            // This should ideally not be reached if index.php filters correctly,
            // but it's a safeguard.
            $api->sendError("Invalid API request format. Expecting /api/resource[/id]", 400);
            return;
        }
        
        $resource = $urlParts[1] ?? null;
        $id = $urlParts[2] ?? null;

        if ($resource) {
            $resourceControllerPath = CONTROLLER . "api/" . $resource . "ApiController.tpl";
            if (file_exists($resourceControllerPath)) {
                require_once $resourceControllerPath;
                $resourceControllerName = $resource . "ApiController";
                if (class_exists($resourceControllerName)) {
                    $resourceController = new $resourceControllerName();
                    // Pass the HTTP method, ID, and any request data (e.g., from POST)
                    $resourceController->handleRequest($_SERVER['REQUEST_METHOD'], $id, $_REQUEST);
                } else {
                    $api->sendError("API endpoint controller class not found: " . $resourceControllerName, 500);
                }
            } else {
                $api->sendError("API endpoint not found: " . $resource, 404);
            }
        } else {
            // This case means the URL was just "/api" or "/api/"
            $api->sendError("API resource not specified", 400);
        }
    }
}
?>
