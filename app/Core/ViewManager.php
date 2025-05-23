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
        // Ensure INC is defined and ends with a slash if needed, or adjust path concatenation.
        // Assuming INC is like "includes/"
        $this->headerTplPath     = INC . $templateNamePrefix . "Header.tpl";
        $this->navigationTplPath = INC . $templateNamePrefix . "Navigation.tpl";
        $this->footerTplPath     = INC . $templateNamePrefix . "Footer.tpl";
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
<<<<<<< HEAD
        $viewFilePath = VIEW . strtolower($bodyViewName) . ".tpl";
=======
        
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
>>>>>>> cc4ee689bdc1f642d96f230e284fd6de713c3d61

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
