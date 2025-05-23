<?php
    // CoreEssentials class is expected to be available (e.g. included via config.tpl or autoloader)
    class CoreRouter {
        private $templateNamePrefix; // Changed from essentialsClassName
        private $defaultViewName;
        private $defaultTitle;
        private $standaloneRoutes;
        private $essentialsInstance;

        public function __construct(
            string $templateNamePrefix, // Changed from essentialsClassName
            string $defaultViewName,
            string $defaultTitle,
            array $standaloneRoutes = []
        ) {
            $this->templateNamePrefix = $templateNamePrefix; // Changed from essentialsClassName
            $this->defaultViewName = $defaultViewName;
            $this->defaultTitle = $defaultTitle;
            $this->standaloneRoutes = $standaloneRoutes;

            // CoreEssentials is expected to be included/available globally.
            if (!class_exists("CoreEssentials")) {
                error_log("CoreRouter Error: Essentials class 'CoreEssentials' not found. Make sure view/CoreEssentials.tpl is included.");
                return; 
            }
            // Instantiate CoreEssentials, passing the template name prefix
            $this->essentialsInstance = new CoreEssentials($this->templateNamePrefix);

            $this->route();
        }

        private function route() {
            $url = (isset($_GET['url'])) ? $_GET['url'] : false;
            $url = rtrim($url, "/");
            // Ensure $url is an array even if empty or not set after explode
            $url = ($url === false || $url === "") ? [] : explode("/", $url);

            $viewName = '';
            $title = '';
            $argument = '';
            $isStandalonePage = false;

            if (empty($url[0])) { // No URL or root access (e.g. example.com/ or example.com/index.php)
                $viewName = $this->defaultViewName;
                $title = $this->defaultTitle;
                $isStandalonePage = in_array($this->defaultViewName, $this->standaloneRoutes);
            } else {
                $controllerNameFromUrl = $url[0];
                $viewName = $controllerNameFromUrl;
                // Title usually matches controller/view name unless specified otherwise.
                // For simplicity, using the view name as title.
                $title = $controllerNameFromUrl; 
                $isStandalonePage = in_array($controllerNameFromUrl, $this->standaloneRoutes);

                if (isset($url[1])) {
                    $argument = $url[1];
                }
            }
            
            // Call the body method of the instantiated essentials class
            if ($this->essentialsInstance && method_exists($this->essentialsInstance, 'body')) {
                $this->essentialsInstance->body($viewName, $title, $argument, $isStandalonePage);
            } else {
                if (!$this->essentialsInstance) {
                     error_log("CoreRouter Error: Essentials instance of 'CoreEssentials' was not created (likely class 'CoreEssentials' not found). Cannot call 'body'.");
                } else {
                     error_log("CoreRouter Error: Method 'body' not found in class 'CoreEssentials'.");
                }
            }
        }
    }
?>
