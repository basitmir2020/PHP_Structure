<?php
namespace App\Controller\Public;

use App\Core\ViewManager; // Use statement for ViewManager

class HomeController {
    private $viewManager;

    public function __construct(ViewManager $viewManager) {
        $this->viewManager = $viewManager;
    }

    public function index() {
        // Parameters for ViewManager->body: viewPath, title, args, noInclude
        $this->viewManager->body('public/index', 'Welcome Home', null, false);
    }
}
?>
