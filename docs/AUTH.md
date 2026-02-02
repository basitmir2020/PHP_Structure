# Admin & Authentication Setup

This framework comes with a built-in authentication system (Login, Forgot Password, Dashboard).

## 🚀 Features
*   **Secure Login**: Uses `password_verify` and Session fixation protection.
*   **Forgot Password**: Generates secure tokens (expires in 1 hour).
*   **Admin Dashboard**: Protected route (`/admin/dashboard`) requiring login.
*   **Default Admin**: Created automatically by `bin/setup.php`.

---

## ⚙️ Configuration

### 1. Database Setup
The **Accounts** table is automatically created by `bin/setup.php` with the following columns:
*   `email` (Username)
*   `password` (Hashed)
*   `reset_token` & `reset_expires` (For password recovery)

### 2. Default Login
After running setup, you can log in immediately:
*   **URL**: `/public/login`
*   **Email**: `admin@example.com`
*   **Password**: `admin123`

### 3. Mail Configuration (For Password Reset)
To make the "Forgot Password" feature send actual emails, open `app/Config/Config.php` and configure your SMTP settings (if you add an SMTP library) or standard PHP `mail()` settings.
*   Update `SENDER_EMAIL` and `SENDER_NAME` constants.
*   *Note: The current implementation simulates email sending for development. You will need to integrate a library like PHPMailer or SwiftMailer for production email delivery.*

---

## 🔒 Protected Routes
To protect a new controller, simply add this check in the `__construct` method:

```php
public function __construct(ViewManager $viewManager) {
    if (empty($_SESSION['auth_user_id'])) {
        header("Location: " . WEBPATH . "public/login");
        exit;
    }
}
```
