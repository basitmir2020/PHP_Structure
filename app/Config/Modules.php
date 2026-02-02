<?php
namespace App\Config;

class Modules {
    /**
     * Returns the list of registered modules and their configuration.
     * The key is the URL segment (case-insensitive in routing, but conventionally capitalized here).
     * 
     * @return array
     */
    public static function getList() {
        // You can add more modules here easily.
        // Example: 'Admin' => ['default_controller' => 'Auth', 'default_method' => 'index']
        return [
            'Public' => [
                'default_controller' => 'Home',
                'default_method' => 'index'
            ],
            'Admin' => [
                'default_controller' => 'Auth', // Admin typically starts with Login/Auth
                'default_method' => 'index'
            ]
        ];
    }

    public static function getDefaultModule() {
        return $_ENV['DEFAULT_MODULE'] ?? 'Public';
    }
}
