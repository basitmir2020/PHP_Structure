<?php
namespace App\Controller\Public;

use App\Core\ViewManager;
use App\Persistence\DbContext;
use App\Util\Security;

class ForgotPasswordController
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
        $this->viewManager->body('public/forgot_password', 'Forgot Password', null, true);
    }

    public function process()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . WEBPATH . "public/forgotpassword");
            exit;
        }

        $email = Security::sanitize($_POST['email'] ?? '', 'email');
        if (!$email) {
            $this->viewManager->body('public/forgot_password', 'Forgot Password', 'Valid email required.', true);
            return;
        }

        // Check if user exists
        $user = $this->db->select("SELECT id FROM accounts WHERE email = ?", [$email]);

        if ($user) {
            // Generate Token
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $this->db->update(
                "UPDATE accounts SET reset_token = ?, reset_expires = ? WHERE email = ?",
                [$token, $expiry, $email]
            );

            // In a real app, send email here.
            // For now, we simulate success message.
            // If Mail config was set up, we'd use mail().

            // To allow testing without mail server, we'll log it or just show it (in DEBUG mode only)
            // But for production safety, we just say "If that email exists, we sent a link."

            $msg = "If an account matches that email, a reset link has been sent.";

            // TODO: Implement actual Email Service using Config::SMTP settings
        } else {
            $msg = "If an account matches that email, a reset link has been sent.";
        }

        $this->viewManager->body('public/forgot_password_sent', 'Check your Email', $msg, true);
    }
}
