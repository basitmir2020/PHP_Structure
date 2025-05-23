<?php
    class CoreRouter {
        private $essentialsClassName;
        private $defaultViewName;
        private $defaultTitle;
        private $standaloneRoutes;
        private $essentialsInstance;

        public function __construct(
            string $essentialsClassName,
            string $defaultViewName,
            string $defaultTitle,
            array $standaloneRoutes = []
        ) {
            $this->essentialsClassName = $essentialsClassName;
            $this->defaultViewName = $defaultViewName;
            $this->defaultTitle = $defaultTitle;
            $this->standaloneRoutes = $standaloneRoutes;

            // Instantiate the essentials class
            // Assumes the class file is already included (e.g., via config.tpl)
            if (!class_exists($this->essentialsClassName)) {
                error_log("CoreRouter Error: Essentials class '{$this->essentialsClassName}' not found.");
                // Depending on desired behavior, could throw an exception or return to stop execution.
                // For now, as per prompt, assume it will exist or log and continue (which might lead to errors later).
                // To be robust, an exception or early return would be better if class is critical.
                // However, if essentialsInstance is not created, method_exists check later will fail.
                return; 
            }
            $this->essentialsInstance = new $this->essentialsClassName();

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
                // No specific "index" override is needed here as per prompt,
                // default behavior covers $url[0] becoming the viewName.
            }
            
            // Call the body method of the instantiated essentials class
            if ($this->essentialsInstance && method_exists($this->essentialsInstance, 'body')) {
                $this->essentialsInstance->body($viewName, $title, $argument, $isStandalonePage);
            } else {
                if (!$this->essentialsInstance) {
                     error_log("CoreRouter Error: Essentials instance of '{$this->essentialsClassName}' was not created (likely class not found). Cannot call 'body'.");
                } else {
                     error_log("CoreRouter Error: Method 'body' not found in class '{$this->essentialsClassName}'.");
                }
                // Potentially display a generic error page to the user.
                // For example:
                // echo "A critical error occurred. Please contact support.";
                // exit;
            }
        }
    }
?>
