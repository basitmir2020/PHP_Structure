# Installation Guide

Follow these steps to set up the framework for a new project.

## 📋 Prerequisites
Ensure your environment meets these requirements:
*   **PHP**: Version 7.4 or 8.0+
*   **Database**: MySQL 5.7+ or MariaDB 10.2+
*   **Web Server**: Apache or Nginx
*   **Composer**: For dependency management (recommended)

---

## 🛠️ Step-by-Step Installation

### 1. Download & Prepare
Clone the repository or download the source code to your working directory.
```bash
git clone https://github.com/your-repo/php-structure.git
cd php-structure
```

### 2. Configuration
The framework needs a configuration file to store database credentials and API keys.

1.  **Create Config File**:
    Copy the template to the creation location.
    ```bash
    cp app/Config/ConfigTemplate.php app/Config/Config.php
    ```
    *(Note: On Windows, use `copy app\Config\ConfigTemplate.php app\Config\Config.php`)*

2.  **Edit Credentials**:
    Open `app/Config/Config.php` and update the database settings:
    ```php
    const DB_HOST = 'localhost';
    const DB_NAME = 'your_project_db';
    const DB_USER = 'your_db_user';
    const DB_PASS = 'your_db_password';
    ```

### 3. Initialize Database & Customization
We provide an interactive setup script to configure your project and create the database.

Run the following command from the project root:
```bash
php bin/setup.php
```
*   **Interactive Prompts**: You will be asked for the **Project Name** and **Installation Folder**.
*   **Automation**: The script will automatically:
    *   Generate `app/Config/Config.php` with the correct URL paths and Database Name.
    *   Update `composer.json` with your project name.
    *   Create the database and tables.
*   **Failure**: If it fails, check your credentials in `Config.php` and run the script again (or manually edit Config.php).

### 4. Install Dependencies
If you have Composer installed, run:
```bash
composer install
```
*If you don't use Composer, the framework includes a fallback autoloader, but manual package management is harder.*

---

## 🌐 Web Server Configuration

**CRITICAL**: You must point your web server's "Document Root" to the `public/` directory. **Do not** point it to the project root.

### Apache
1.  Enable `mod_rewrite`.
2.  Point `DocumentRoot` to `/path/to/project/public`.
3.  Ensure `.htaccess` overrides are allowed.

**Example VirtualHost:**
```apache
<VirtualHost *:80>
    ServerName project.local
    DocumentRoot "/var/www/project/public"
    
    <Directory "/var/www/project/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Nginx
Configure your server block to direct traffic to `index.php` in the `public` folder.

**Example Block:**
```nginx
server {
    listen 80;
    server_name project.local;
    root /var/www/project/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?url=$uri&$args;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    }
}
```

---

## ✅ Verification
1.  Start your web server.
2.  Navigate to your URL (e.g., `http://localhost/project/public` or `http://project.local`).
3.  You should see the "Welcome" page.
4.  Visit `/public/demo` to verify that routing and the database are working correctly.

## 🔐 Built-in Authentication
The framework includes a fully functional Login and Password Reset system.
*   **Default Admin**: Email: `admin@example.com`, Password: `admin123`
*   **Login URL**: `/public/login`
*   **Dashboard**: `/admin/dashboard`
*   See [**docs/AUTH.md**](AUTH.md) for full details.
