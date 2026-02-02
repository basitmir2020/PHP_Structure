<?php
// Defines simple setup logic
$root = dirname(__DIR__);
echo "Setting up PHP Boilerplate...\n";

// 1. Copy .env
if (!file_exists($root . '/.env')) {
    if (file_exists($root . '/.env.example')) {
        copy($root . '/.env.example', $root . '/.env');
        echo "Created .env file.\n";
    } else {
        echo "Error: .env.example not found.\n";
    }
} else {
    echo ".env already exists.\n";
}

// 2. Load Env (Manual, since Composer might not be run)
$env = [];
if (file_exists($root . '/.env')) {
    $lines = file($root . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0)
            continue;
        list($name, $value) = explode('=', $line, 2);
        $env[trim($name)] = trim($value);
    }
}

// 3. Database Setup
echo "setting up Database... (Host: " . ($env['DB_HOST'] ?? 'unknown') . ")\n";
if (isset($env['DB_HOST'], $env['DB_NAME'], $env['DB_USER'])) {
    try {
        $dsn = "mysql:host={$env['DB_HOST']};charset=utf8mb4";
        $pdo = new PDO($dsn, $env['DB_USER'], $env['DB_PASS'] ?? '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create Database
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$env['DB_NAME']}`");
        echo "Database `{$env['DB_NAME']}` check/creation successful.\n";

        $pdo->exec("USE `{$env['DB_NAME']}`");

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
    echo "Skipping Database setup: Credentials missing in .env.\n";
}

echo "\nSetup Complete!\n";
echo "Run 'composer install' to install dependencies.\n";
echo "Point your web server to '{$root}/public'.\n";
