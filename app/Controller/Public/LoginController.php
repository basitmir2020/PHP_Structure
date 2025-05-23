<?php
namespace App\Controller\Public;

use App\Core\ViewManager;

class LoginController {
    private $viewManager;

    public function __construct(ViewManager $viewManager) {
        $this->viewManager = $viewManager;
    }

    public function index() {
        // Assuming 'public/login' view is standalone (no main header/footer)
        $this->viewManager->body('public/login', 'Login', null, true);
    }
}
?>
