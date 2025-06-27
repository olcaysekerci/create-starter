<?php

return [
    'name' => 'Settings',
    'enabled' => true,
    'settings' => [
        'mail' => [
            'enabled' => true,
            'driver' => env('MAIL_MAILER', 'smtp'),
            'host' => env('MAIL_HOST', 'mailpit'),
            'port' => env('MAIL_PORT', 1025),
            'username' => env('MAIL_USERNAME', null),
            'password' => env('MAIL_PASSWORD', null),
            'encryption' => env('MAIL_ENCRYPTION', null),
            'from_address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
            'from_name' => env('MAIL_FROM_NAME', 'Application'),
        ],
        'app' => [
            'name' => env('APP_NAME', 'Laravel'),
            'url' => env('APP_URL', 'http://localhost'),
            'timezone' => env('APP_TIMEZONE', 'UTC'),
            'locale' => env('APP_LOCALE', 'en'),
        ],
        'system' => [
            'maintenance_mode' => env('APP_MAINTENANCE_MODE', false),
            'debug_mode' => env('APP_DEBUG', false),
            'log_level' => env('LOG_LEVEL', 'debug'),
        ],
    ],
    'routes' => [
        'web' => true,
        'admin' => true,
    ],
]; 