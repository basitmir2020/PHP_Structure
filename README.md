# PHP Modular Boilerplate

This project is a modern, secure, and highly configurable PHP boilerplate designed for both MVC web applications and API-first development. It features a namespaced architecture, a public-facing webroot, and a modular design.

## Quick Start (3 Steps)

### 1. Run Setup Script
Open a terminal in the project root and run:
```bash
php bin/setup.php
```
This will:
*   Generate `app/Config/Config.php` from `app/Config/ConfigTemplate.php`.
*   Establish basic database tables (using defaults or your existing Config).

### 2. Configure Project
Open `app/Config/Config.php` and set your credentials:
```php
class Config {
    const DB_HOST = 'localhost';
    const DB_NAME = 'php_structure_db';
    // ...
}
```

### 3. Point Web Server
**CRITICAL SECURITY STEP**: Point your web server (Apache/Nginx/XAMPP) document root to the `public/` folder.
*   **URL Example**: `http://localhost/PHP_Structure/public/`

---

## Key Features

### 🔐 Security First
*   **Public Directory**: `index.php` and assets are in `public/`.
*   **PHP Configuration**: Credentials are stored in `app/Config/Config.php` (git-ignored) - no external parsing needed.
*   **Security Headers**: Built-in protection (CSP, X-Frame-Options) in `app/Config/Bootstrap.php`.

### 🧩 Modular Architecture
Easily add new sections (e.g., "VendorPortal") via `app/Config/Modules.php`.

### 🔌 API Ready
*   **API Prefix**: Routes starting with `/api` are automatically handled.
*   **Frontend Ready**: Configure `CORS_ALLOWED_ORIGINS` in `app/Config/Config.php`.

## Project Structure

```
/
├── bin/
├── public/             <-- DOCUMENT ROOT
├── app/
├── docs/               <-- DOCUMENTATION
│   ├── DATABASE.md
│   ├── ROUTING.md
│   └── SECURITY.md
└── composer.json
```

## 📚 Documentation
*   [**Database & Stored Procedures**](docs/DATABASE.md): Learn how to query data and call procedures.
*   [**Routing & Architecture**](docs/ROUTING.md): Understand the folder structure and URL mapping.
*   [**Security Guidelines**](docs/SECURITY.md): Best practices for keeping the app secure.
*   [**Demo Pages**](public/demo): Visit `/public/demo` in your browser for a live interactive guide.

## Api Usage

The base path for all API routes is `/api`.
**Authentication**: Include `X-API-KEY` header with a key defined in `app/Config/Config.php`.

*   **GET** `/api/accounts`: List accounts.
*   **GET** `/api/accounts/{id}`: Get account details.
*   **POST** `/api/accounts`: Create account.

## Dependencies

*   **Composer**: Run `composer install` to manage dependencies.
*   **Fallback**: A manual autoloader is included for environments without Composer access.
