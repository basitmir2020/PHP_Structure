<?php
namespace App\Core;

// CoreEssentials class (now ViewManager) is expected to be available via autoloader.
class CoreRouter {
    private $templateNamePrefix; 
    private $defaultViewName;
    private $defaultTitle;
    private $standaloneRoutes;
    private $essentialsInstance; // Should now be an instance of ViewManager

    public function __construct(
        string $templateNamePrefix, 
        string $defaultViewName,
        string $defaultTitle,
        array $standaloneRoutes = []
    ) {
        $this->templateNamePrefix = $templateNamePrefix; 
        $this->defaultViewName = $defaultViewName;
        $this->defaultTitle = $defaultTitle;
        $this->standaloneRoutes = $standaloneRoutes;

        // ViewManager is expected to be included/available via autoloader.
        if (!class_exists("\\App\\Core\\ViewManager")) { // Updated class name check
            error_log("CoreRouter Error: View manager class '\\App\\Core\\ViewManager' not found. Make sure app/Core/ViewManager.php is available and autoloadable.");
            return; 
        }
        // Instantiate ViewManager, passing the template name prefix
        $this->essentialsInstance = new \App\Core\ViewManager($this->templateNamePrefix); // Updated instantiation

        $this->route();
    }

    private function route() {
        $url = (isset($_GET['url'])) ? $_GET['url'] : false;
        $url = rtrim($url, "/");
        $url = ($url === false || $url === "") ? [] : explode("/", $url);

        $viewName = '';
        $title = '';
        $argument = '';
        $isStandalonePage = false;

        if (empty($url[0])) { 
            $viewName = $this->defaultViewName;
            $title = $this->defaultTitle;
            $isStandalonePage = in_array($this->defaultViewName, $this->standaloneRoutes);
        } else {
            $controllerNameFromUrl = $url[0];
            $viewName = $controllerNameFromUrl;
            $title = $controllerNameFromUrl; 
            $isStandalonePage = in_array($controllerNameFromUrl, $this->standaloneRoutes);

            if (isset($url[1])) {
                $argument = $url[1];
            }
        }
        
        if ($this->essentialsInstance && method_exists($this->essentialsInstance, 'body')) {
            $this->essentialsInstance->body($viewName, $title, $argument, $isStandalonePage);
        } else {
            if (!$this->essentialsInstance) {
                 error_log("CoreRouter Error: Essentials instance of '\\App\\Core\\ViewManager' was not created (likely class not found). Cannot call 'body'."); // Updated error message
            } else {
                 error_log("CoreRouter Error: Method 'body' not found in class '\\App\\Core\\ViewManager'."); // Updated error message
            }
        }
    }
}
?>
