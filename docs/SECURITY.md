# Security Guidelines & Best Practices

This document outlines the mandatory security practices for this project. All developers must adhere to these guidelines to ensure the application remains secure.

## 1. Cross-Site Scripting (XSS) Prevention

**Rule:** NEVER trust user input. Always escape data before outputting it to the browser.
**Tool:** Use `App\Util\Security::escape()`

### Usage:
```php
use App\Util\Security;

// Bad
echo "Hello " . $_GET['name'];

// Good
echo "Hello " . Security::escape($_GET['name']);
```

## 2. Cross-Site Request Forgery (CSRF) Protection

**Rule:** All state-changing requests (POST, PUT, DELETE) must include a valid CSRF token.
**Tool:** Use `App\Util\Security::generateCSRFToken()` and `App\Util\Security::validateCSRFToken()`

### Usage:

**In your Controller/View (Form Generation):**
```php
// In the PHP block before HTML
$csrfToken = \App\Util\Security::generateCSRFToken();
?>

<!-- In the HTML Form -->
<form method="POST" action="/submit">
    <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
    <!-- other fields -->
</form>
```

**In your Controller (Form Processing):**
```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!\App\Util\Security::validateCSRFToken($token)) {
        // Invalid or missing token
        die("CSRF Token Validation Failed");
    }
    // Proceed with processing
}
```

## 3. SQL Injection Prevention

**Rule:** NEVER concatenate user input into SQL strings. Always use Parameterized Queries.
**Tool:** Use `App\Persistence\DbContext` methods (`select`, `insert`, `update`, `delete`) with binding parameters.

### Usage:
```php
// Bad
$sql = "SELECT * FROM users WHERE email = '" . $email . "'";

// Good
$sql = "SELECT * FROM users WHERE email = ?";
$result = $db->select($sql, [$email]);
```

## 4. Input Validation & Sanitization

**Rule:** Validate strict types and formats on input. Sanitize where appropriate.
**Tool:** Use `App\Util\Validator` for validation and `App\Util\Security::sanitize()` for cleaning.

### Usage:
```php
// Sanitization
$cleanEmail = \App\Util\Security::sanitize($_POST['email'], 'email');

// Validation
$validator = new \App\Util\Validator();
$isValid = $validator->validate(['email' => $cleanEmail], ['email' => ['required', 'email']]);
```

## 5. Configuration Security

**Rule:** Never commit sensitive credentials to version control.
- `app/Config/Config.php` is ignored by git.
- Use `app/Config/ConfigTemplate.php` for structure reference.
- Ensure `debug` mode is OFF in production.
