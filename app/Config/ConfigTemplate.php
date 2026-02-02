<?php
namespace App\Config;

class ConfigTemplate
{
    // Database Configuration
    const DB_HOST = 'localhost';
    const DB_NAME = 'php_structure_db';
    const DB_USER = 'root';
    const DB_PASS = '';

    // Application Settings
    const APP_URL = 'http://localhost/PHP_Structure/public';
    const APP_ENV = 'local'; // Options: local, production
    const APP_KEY = ''; // Generate a 32-byte key (base64 encoded) for cookie encryption

    const SENDER_EMAIL = 'no-reply@example.com';
    const SENDER_NAME = 'My App';

    // Security
    const API_KEYS = ['your-super-secret-api-key-12345'];
    const CORS_ALLOWED_ORIGINS = ['*'];
    const SESSION_LIFETIME = 3600; // Session lifetime in seconds (default 1 hour)
}
