<?php
namespace Config;

class Config {
    private static $config = [];

    public static function load() {
        self::$config = [
            'db' => [
                'host' => getenv('DB_HOST') ?: 'db',
                'user' => getenv('DB_USER') ?: 'user',
                'pass' => getenv('DB_PASS') ?: 'password',
                'name' => getenv('DB_NAME') ?: 'database',
                'charset' => getenv('DB_CHARSET') ?: 'utf8mb4'
            ],
            'cors' => [
                'allowed_origins' => getenv('CORS_ALLOWED_ORIGINS') ?: '*',
                'allowed_methods' => getenv('CORS_ALLOWED_METHODS') ?: 'GET, POST, OPTIONS',
                'allowed_headers' => getenv('CORS_ALLOWED_HEADERS') ?: 'Content-Type'
            ],
            'security' => [
                'display_errors' => getenv('DISPLAY_ERRORS') ?: '0'
            ]
        ];
    }

    public static function get($key, $default = null) {
        $keys = explode('.', $key);
        $config = self::$config;

        foreach ($keys as $k) {
            if (!isset($config[$k])) {
                return $default;
            }
            $config = $config[$k];
        }

        return $config;
    }
}
