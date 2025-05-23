<?php
namespace App\Controller\Admin;

use App\Core\ViewManager;

class AuthController {
    private $viewManager;

    public function __construct(ViewManager $viewManager) {
        $this->viewManager = $viewManager;
    }

    public function index() {
        // Admin login page, typically standalone
        // This uses view/admin/index.tpl as the login page view
        $this->viewManager->body('admin/index', 'Admin Portal Login', null, true);
    }
}
?>
