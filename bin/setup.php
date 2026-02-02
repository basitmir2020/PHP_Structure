<?php
// Defines simple setup logic
$root = dirname(__DIR__);
echo "Setting up PHP Boilerplate...\n";

// 1. Interactive Configuration
echo "--------------------------------------------------\n";
echo "Welcome to the Project Setup Wizard\n";
echo "--------------------------------------------------\n";

$projectName = readline("Enter Project Name (e.g. My Cool App): ");
$projectSlug = strtolower(trim(str_replace(' ', '-', $projectName)));
if (empty($projectSlug))
    $projectSlug = 'php-structure';

$folderName = readline("Enter Installation Folder Name (e.g. php-structure): ");
if (empty($folderName))
    $folderName = 'PHP_Structure';

echo "\nConfiguring project as: '$projectName' ($projectSlug)\n";

// 2. Create Config.php
$configPath = $root . '/app/Config/Config.php';
$templatePath = $root . '/app/Config/ConfigTemplate.php';

if (!file_exists($configPath)) {
    if (file_exists($templatePath)) {
        $configContent = file_get_contents($templatePath);

        // Dynamic Replacements
        $configContent = str_replace('php_structure_db', str_replace('-', '_', $projectSlug) . '_db', $configContent);
        $configContent = str_replace('/PHP_Structure/', "/$folderName/", $configContent);

        file_put_contents($configPath, $configContent);
        echo "Created app/Config/Config.php with custom settings.\n";
    } else {
        echo "Error: app/Config/ConfigTemplate.php not found.\n";
        exit(1);
    }
} else {
    echo "app/Config/Config.php already exists. Skipping creation.\n";
}

// 3. Update composer.json
$composerPath = $root . '/composer.json';
if (file_exists($composerPath)) {
    $composerJson = json_decode(file_get_contents($composerPath), true);
    if ($composerJson) {
        $composerJson['name'] = "custom-project/$projectSlug";
        $composerJson['description'] = $projectName;
        file_put_contents($composerPath, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        echo "Updated composer.json identity.\n";
    }
}

// 2. Load Config to setup Database
require_once $configPath;
use App\Config\Config;

// 3. Database Setup
echo "setting up Database... (Host: " . Config::DB_HOST . ")\n";

if (defined('App\Config\Config::DB_HOST') || (class_exists('App\Config\Config') && Config::DB_HOST)) {
    try {
        $dsn = "mysql:host=" . Config::DB_HOST . ";charset=utf8mb4";
        $pdo = new PDO($dsn, Config::DB_USER, Config::DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create Database
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . Config::DB_NAME . "`");
        echo "Database `" . Config::DB_NAME . "` check/creation successful.\n";

        $pdo->exec("USE `" . Config::DB_NAME . "`");

        // Create Accounts Table
        $sql = "CREATE TABLE IF NOT EXISTS accounts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            reset_token VARCHAR(64) NULL,
            reset_expires DATETIME NULL
        )";
        $pdo->exec($sql);
        echo "Table `accounts` check/creation successful.\n";

        // Insert Default Admin if not exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM accounts WHERE email = ?");
        $stmt->execute(['admin@example.com']);
        if ($stmt->fetchColumn() == 0) {
            $pass = password_hash('admin123', PASSWORD_DEFAULT);
            $pdo->prepare("INSERT INTO accounts (name, email, password) VALUES (?, ?, ?)")
                ->execute(['Admin', 'admin@example.com', $pass]);
            echo "Default Admin User created (admin@example.com / admin123)\n";
        }

    } catch (PDOException $e) {
        echo "Database Setup Failed: " . $e->getMessage() . "\n";
    }
} else {
    echo "Skipping Database setup: Credentials missing in Config.\n";
}

echo "\nSetup Complete!\n";
echo "Run 'composer install' to install dependencies (if any).\n";
echo "Point your web server to '{$root}/public'.\n";
