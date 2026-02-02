<?php
namespace App\Controller\Public;

use App\Core\ViewManager;
use App\Persistence\DbContext;

class ResetPasswordController
{
    private $viewManager;
    private $db;

    public function __construct(ViewManager $viewManager)
    {
        $this->viewManager = $viewManager;
        $this->db = new DbContext();
    }

    public function index($token = '')
    {
        if (!$token) {
            header("Location: " . WEBPATH . "public/login");
            exit;
        }

        // Verify Token
        $user = $this->db->select("SELECT id FROM accounts WHERE reset_token = ? AND reset_expires > NOW()", [$token]);

        if (!$user) {
            $this->viewManager->body('public/reset_error', 'Invalid Token', 'This password reset link is invalid or has expired.', true);
            return;
        }

        $this->viewManager->body('public/reset_password', 'Reset Password', ['token' => $token], true);
    }

    public function process()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . WEBPATH . "public/login");
            exit;
        }

        $token = $_POST['token'] ?? '';
        $pass = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (!$pass || $pass !== $confirm) {
            $this->viewManager->body('public/reset_password', 'Reset Password', ['token' => $token, 'error' => 'Passwords do not match.'], true);
            return;
        }

        // Verify Token Again
        $users = $this->db->select("SELECT id FROM accounts WHERE reset_token = ? AND reset_expires > NOW()", [$token]);

        if (count($users) === 1) {
            $newHash = password_hash($pass, PASSWORD_DEFAULT);
            $this->db->update(
                "UPDATE accounts SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?",
                [$newHash, $users[0]['id']]
            );

            // Redirect to login
            header("Location: " . WEBPATH . "public/login?msg=changed");
            exit;
        }

        $this->viewManager->body('public/reset_error', 'Error', 'Invalid or expired token.', true);
    }
}
