<?php
// Defines simple setup logic
$root = dirname(__DIR__);
echo "Setting up PHP Boilerplate...\n";

// 1. Create Config.php
$configPath = $root . '/app/Config/Config.php';
$templatePath = $root . '/app/Config/ConfigTemplate.php';

if (!file_exists($configPath)) {
    if (file_exists($templatePath)) {
        copy($templatePath, $configPath);
        echo "Created app/Config/Config.php from template.\n";
    } else {
        echo "Error: app/Config/ConfigTemplate.php not found.\n";
        exit(1);
    }
} else {
    echo "app/Config/Config.php already exists.\n";
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
            email VARCHAR(255) NOT NULL UNIQUE
        )";
        $pdo->exec($sql);
        echo "Table `accounts` check/creation successful.\n";

    } catch (PDOException $e) {
        echo "Database Setup Failed: " . $e->getMessage() . "\n";
    }
} else {
    echo "Skipping Database setup: Credentials missing in Config.\n";
}

echo "\nSetup Complete!\n";
echo "Run 'composer install' to install dependencies (if any).\n";
echo "Point your web server to '{$root}/public'.\n";
