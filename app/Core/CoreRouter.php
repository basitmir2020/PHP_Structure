<?php
namespace App\Core;

// CoreEssentials class (now ViewManager) is expected to be available via autoloader.
class CoreRouter {
    private $modulePath; // Added modulePath property
    private $templateNamePrefix; 
    private $defaultViewName;
    private $defaultTitle;
    private $standaloneRoutes;
    private $essentialsInstance; // Should now be an instance of ViewManager

    public function __construct(
        string $modulePath, // Added modulePath parameter
        string $templateNamePrefix, 
        string $defaultViewName,
        string $defaultTitle,
        array $standaloneRoutes = []
    ) {
        $this->modulePath = $modulePath; // Assign modulePath
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

        $actualViewNamePart = ''; // The part like "index" or "login" or $url[0]
        $title = '';
        $argument = '';
        $isStandalonePage = false;

        if (empty($url[0])) { // No URL or root access
            $actualViewNamePart = $this->defaultViewName;
            $title = $this->defaultTitle;
            $isStandalonePage = in_array($this->defaultViewName, $this->standaloneRoutes);
            // $argument remains empty
        } else {
            $actualViewNamePart = $url[0];
            $title = $url[0]; // Title usually matches controller/view name
            $isStandalonePage = in_array($actualViewNamePart, $this->standaloneRoutes);

            if (isset($url[1])) {
                $argument = $url[1];
            }
        }

        $viewPathForViewManager = $this->modulePath . '/' . $actualViewNamePart;
        
        if ($this->essentialsInstance && method_exists($this->essentialsInstance, 'body')) {
            $this->essentialsInstance->body($viewPathForViewManager, $title, $argument, $isStandalonePage); // Pass updated view path
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
