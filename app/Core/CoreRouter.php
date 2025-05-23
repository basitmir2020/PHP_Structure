<?php
namespace App\Core;

// ViewManager class is expected to be available via autoloader.
class CoreRouter {
    private $modulePath; 
    // private $templateNamePrefix; // Removed
    private $defaultControllerName; 
    private $defaultMethodName;     
    private $essentialsInstance; // Instance of ViewManager

    public function __construct(
        string $modulePath, 
        // string $templateNamePrefix, // Removed
        string $defaultControllerName, 
        string $defaultMethodName = 'index'
    ) {
        $this->modulePath = $modulePath; 
        // $this->templateNamePrefix = $templateNamePrefix; // Removed assignment
        $this->defaultControllerName = $defaultControllerName; 
        $this->defaultMethodName = $defaultMethodName;       

        // ViewManager is expected to be included/available via autoloader.
        if (!class_exists("\\App\\Core\\ViewManager")) { 
            error_log("CoreRouter Error: View manager class '\\App\\Core\\ViewManager' not found. Make sure app/Core/ViewManager.php is available and autoloadable.");
            return; 
        }
        // Instantiate ViewManager, passing the lowercased modulePath as context
        $this->essentialsInstance = new \App\Core\ViewManager(strtolower($this->modulePath)); 

        $this->route();
    }

    private function route() {
        $url = (isset($_GET['url'])) ? $_GET['url'] : false;
        $url = rtrim($url, "/");
        $url = ($url === false || $url === "") ? [] : explode("/", $url); // Ensure $url is always an array

        $controllerName = ucfirst(strtolower($url[0] ?? $this->defaultControllerName));
        // Ensure modulePath (e.g., "Public", "Admin") is correctly cased for namespace
        $moduleNamespacePart = ucfirst(strtolower($this->modulePath)); 
        $controllerClassName = "App\\Controller\\" . $moduleNamespacePart . "\\" . $controllerName . "Controller";

        // Determine method name
        if (isset($url[1]) && !empty($url[1])) {
            $methodName = $url[1]; // Use method name from URL if present
        } else {
            $methodName = $this->defaultMethodName; // Default to 'index' or specified default
        }

        $arguments = array_slice($url, 2);

        if (class_exists($controllerClassName)) {
            $controllerInstance = new $controllerClassName($this->essentialsInstance); // Pass ViewManager

            if (method_exists($controllerInstance, $methodName)) {
                call_user_func_array([$controllerInstance, $methodName], $arguments);
            } else {
                // Method not found - 404
                error_log("CoreRouter Error: Method '{$methodName}' not found in controller '{$controllerClassName}'.");
                if ($this->essentialsInstance && method_exists($this->essentialsInstance, 'body')) {
                     $this->essentialsInstance->body($this->modulePath . '/404', 'Page Not Found', null, true);
                } else {
                    header("HTTP/1.0 404 Not Found");
                    echo "<h1>404 - Page Not Found (Error: Unable to load error view)</h1>";
                }
                exit;
            }
        } else {
            // Controller class not found - 404
            error_log("CoreRouter Error: Controller class '{$controllerClassName}' not found for module '{$this->modulePath}' and controller part '{$controllerName}'.");
            if ($this->essentialsInstance && method_exists($this->essentialsInstance, 'body')) {
                 $this->essentialsInstance->body($this->modulePath . '/404', 'Page Not Found', null, true);
            } else {
                header("HTTP/1.0 404 Not Found");
                echo "<h1>404 - Page Not Found (Error: Unable to load error view)</h1>";
            }
            exit;
        }
    }
}
?>
