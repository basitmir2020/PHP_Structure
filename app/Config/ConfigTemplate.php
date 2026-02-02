<?php
namespace App\Config;

class Config
{
    // Database Configuration
    const DB_HOST = 'localhost';
    const DB_NAME = 'php_structure_db';
    const DB_USER = 'root';
    const DB_PASS = '';

    // Application Settings
    const APP_URL = 'http://localhost/PHP_Structure/public';
    const APP_ENV = 'local'; // Options: local, production

    // Security
    const API_KEYS = ['your-super-secret-api-key-12345']; // Array of valid API keys
    const CORS_ALLOWED_ORIGINS = ['*']; // Array of allowed origins, e.g. ['http://localhost:3000']
    const SESSION_LIFETIME = 3600; // Session lifetime in seconds (default 1 hour)
}
