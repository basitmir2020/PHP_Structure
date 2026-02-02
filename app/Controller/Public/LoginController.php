<?php
namespace App\Controller\Public;

use App\Core\ViewManager;
use App\Persistence\DbContext;
use App\Util\Security;
use App\Util\SessionManager;

class LoginController
{
    private $viewManager;
    private $db;

    public function __construct(ViewManager $viewManager)
    {
        $this->viewManager = $viewManager;
        $this->db = new DbContext();
    }

    public function index()
    {
        if (!empty($_SESSION['auth_user_id'])) {
            header("Location: " . WEBPATH . "admin/dashboard");
            exit;
        }
        $this->viewManager->body('public/login', 'Login', null, true);
    }

    public function process()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . WEBPATH . "public/login");
            exit;
        }

        $email = Security::sanitize($_POST['email'] ?? '', 'email');
        $password = $_POST['password'] ?? '';

        // Simple validation
        if (!$email || !$password) {
            $this->viewManager->body('public/login', 'Login', 'All fields are required.', true);
            return;
        }

        // Fetch user
        $users = $this->db->select("SELECT id, name, password FROM accounts WHERE email = ?", [$email]);

        if (count($users) === 1) {
            $user = $users[0];
            if (password_verify($password, $user['password'])) {
                // Successful Login
                SessionManager::regenerate();
                $_SESSION['auth_user_id'] = $user['id'];
                $_SESSION['auth_user_name'] = $user['name'];

                header("Location: " . WEBPATH . "admin/dashboard");
                exit;
            }
        }

        $this->viewManager->body('public/login', 'Login', 'Invalid credentials.', true);
    }

    public function logout()
    {
        session_destroy();
        header("Location: " . WEBPATH . "public/login");
        exit;
    }
}
