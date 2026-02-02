# PHP Modular Boilerplate

This project is a modern, secure, and highly configurable PHP boilerplate designed for both MVC web applications and API-first development. It features a namespaced architecture, a public-facing webroot, and a modular design for easy scalability.

## Quick Start (3 Steps)

### 1. Run Setup Script
Open a terminal in the project root and run:
```bash
php bin/setup.php
```
This will:
*   Create your `.env` configuration file from `.env.example`.
*   Establish basic database tables (if credentials are correct in `.env`).

### 2. Configure Environment
Open `.env` and set your database credentials:
```ini
DB_HOST=localhost
DB_NAME=php_structure_db
DB_USER=root
DB_PASS=
```
You can also configure **CORS** here (`CORS_ALLOWED_ORIGINS`) if you are connecting a React/Angular frontend.

### 3. Point Web Server
**CRITICAL SECURITY STEP**: Point your web server (Apache/Nginx/XAMPP) document root to the `public/` folder, NOT the project root.
*   **URL Example**: `http://localhost/PHP_Structure/public/`

---

## Key Features

### 🔐 Security First
*   **Public Directory**: `index.php` and assets (`css`, `js`, `img`) are located in `public/`. Access to core application logic (`app/`) is blocked from the browser.
*   **Environment Variables**: Sensitive credentials are stored in `.env` (git-ignored) using `vlucas/phpdotenv`.
*   **Security Headers**: Built-in protection (CSP, X-Frame-Options, HSTS) applied in `app/Config/Bootstrap.php`.

### 🧩 Modular Architecture
Easily add new sections (e.g., "VendorPortal", "Admin") without touching core files.
1.  **Register**: Add your module to `app/Config/Modules.php`.
    ```php
    'VendorPortal' => ['default_controller' => 'Dashboard', 'default_method' => 'index']
    ```
2.  **Create**: Add your controller in `app/Controller/VendorPortal/DashboardController.php`.
3.  **Access**: Go to `/vendorportal` - routing is handled automatically.

### 🔌 API Ready
*   **Dedicated Routing**: Routes starting with `/api` are handled by `App\Http\ApiRouter`.
*   **Frontend Compatible**: Built-in CORS support allows secure integration with single-page applications (React, Angular, Vue).

## Project Structure

```
/
├── .env                <-- Local configuration (git-ignored)
├── bin/
│   └── setup.php       <-- CLI setup helper
├── public/             <-- DOCUMENT ROOT (Point web server here)
│   ├── index.php       <-- Main entry point
│   ├── css/
│   └── js/
├── app/
│   ├── Config/
│   │   ├── Bootstrap.php <-- App initialization and headers
│   │   └── Modules.php   <-- Module registration
│   ├── Controller/       <-- MVC Controllers (Admin/, Public/)
│   ├── Http/             <-- API Controllers & Router
│   ├── Model/            <-- Data Models
│   ├── Service/          <-- Business Logic
│   └── Core/             <-- Framework Core (Router, ViewManager)
├── view/                 <-- HTML Templates (.tpl)
└── composer.json
```

## Api Usage

The base path for all API routes is `/api`.
**Authentication**: Include `X-API-KEY` header with a key defined in `.env`.

*   **GET** `/api/accounts`: List accounts.
*   **GET** `/api/accounts/{id}`: Get account details.
*   **POST** `/api/accounts`: Create account.

## Dependencies

*   **Composer**: Run `composer install` to manage dependencies.
*   **Fallback**: A manual autoloader is included for environments without Composer access.
