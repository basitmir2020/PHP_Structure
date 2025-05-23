<?php
namespace App\Core;

// Constants INC and VIEW are expected to be defined (typically in config.tpl)
class ViewManager { // Renamed from CoreEssentials
    private $headerTplPath;
    private $navigationTplPath;
    private $footerTplPath;

    /**
     * Constructor for ViewManager.
     *
     * @param string $templateNamePrefix Prefix for template file names. 
     *                                   Example: "" for public site (Header.tpl), 
     *                                   "__Admin__" for admin site (__Admin__Header.tpl).
     */
    public function __construct(string $templateNamePrefix = "") {
        // Assuming ROOT is absolute path to project dir (e.g., C:\xampp\htdocs\PHP_Structure\)
        // and INC is "includes/" (relative to ROOT)
        
        $rootPath = rtrim(ROOT, '/\\'); // Normalize ROOT path (double backslash for literal in string)
        $incPath = trim(INC, '/\\');   // Normalize INC path (double backslash for literal in string)

        // Use DIRECTORY_SEPARATOR for cross-platform compatibility
        $this->headerTplPath     = $rootPath . DIRECTORY_SEPARATOR . $incPath . DIRECTORY_SEPARATOR . $templateNamePrefix . "Header.tpl";
        $this->navigationTplPath = $rootPath . DIRECTORY_SEPARATOR . $incPath . DIRECTORY_SEPARATOR . $templateNamePrefix . "Navigation.tpl";
        $this->footerTplPath     = $rootPath . DIRECTORY_SEPARATOR . $incPath . DIRECTORY_SEPARATOR . $templateNamePrefix . "Footer.tpl";
    }

    private function header($title) {
        // The $title variable will be available within the included header template.
        include $this->headerTplPath;
    }

    private function navigation() {
        include $this->navigationTplPath;
    }

    /**
     * Renders the body content, optionally including header, navigation, and footer.
     *
     * @param string $bodyViewName The name of the main view template file (without .tpl extension).
     * @param string $title The title of the page (passed to the header).
     * @param mixed $arg Optional argument to be available to the body view template.
     * @param bool $noInclude If true, only the body view template is rendered. Otherwise, full layout is rendered.
     */
    public function body(string $bodyViewName, string $title, $arg = false, bool $noInclude = false) {
        // The $arg variable will be available within the included $viewFilePath template.
        
        // New path construction logic for $viewFilePath
        $parts = explode('/', $bodyViewName);
        $viewFile = strtolower(array_pop($parts)) . ".tpl"; // e.g., "index.tpl"
        $moduleSubPath = implode('/', $parts); // e.g., "public" or "admin"

        // ROOT is absolute path to project root, VIEW is relative path from root (e.g., "view/")
        if (!empty($moduleSubPath)) {
            // Ensure correct directory separators, especially for ROOT if it ends with one
            $viewFilePath = rtrim(ROOT, '/\\') . '/' . rtrim(VIEW, '/\\') . '/' . $moduleSubPath . '/' . $viewFile;
        } else {
            // Fallback if no module path, though router should always provide it now
            $viewFilePath = rtrim(ROOT, '/\\') . '/' . rtrim(VIEW, '/\\') . '/' . $viewFile;
        }

        if ($noInclude) {
            include $viewFilePath;
        } else {
            $this->header($title);
            $this->navigation();
            include $viewFilePath;
            $this->footer();
        }
    }

    private function footer() {
        include $this->footerTplPath;
    }
}
?>
