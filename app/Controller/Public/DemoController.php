<?php
namespace App\Controller\Public;

use App\Core\ViewManager;

class DemoController
{
    private $viewManager;

    public function __construct(ViewManager $viewManager)
    {
        $this->viewManager = $viewManager;
    }

    public function index()
    {
        $data = [
            'features' => [
                'MVC Structure' => 'Clean separation of concerns.',
                'Security' => 'Built-in CSRF, XSS protection, and secure Headers.',
                'Database' => 'PDO wrapper with Stored Procedure support.',
                'Routing' => 'Automatic routing based on Module/Controller/Method.'
            ]
        ];
        $this->viewManager->body('public/demo/index', 'Framework Demo', $data);
    }

    public function database()
    {
        // In a real app, you would inject DbContext here.
        // For the demo, we just pass static explanations.
        $this->viewManager->body('public/demo/database', 'Database Features');
    }

    public function security()
    {
        // Generating a dummy CSRF token to display
        $token = \App\Util\Security::generateCSRFToken();
        $this->viewManager->body('public/demo/security', 'Security Features', ['token' => $token]);
    }
}
