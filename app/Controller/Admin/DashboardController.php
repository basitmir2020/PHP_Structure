<?php
namespace App\Controller\Admin;

use App\Core\ViewManager;

class DashboardController
{
    private $viewManager;

    public function __construct(ViewManager $viewManager)
    {
        $this->viewManager = $viewManager;

        // Middleware: Auth Check
        if (empty($_SESSION['auth_user_id'])) {
            header("Location: " . WEBPATH . "public/login");
            exit;
        }
    }

    public function index()
    {
        $this->viewManager->body('admin/dashboard/index', 'Admin Dashboard', [
            'user' => $_SESSION['auth_user_name']
        ]);
    }
}
