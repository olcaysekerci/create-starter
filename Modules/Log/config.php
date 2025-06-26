<?php

return [
    'name' => 'Log',
    'description' => 'Sistem ve kullanıcı aktivite logları modülü - activity log, system log, error log',
    'version' => '1.0.0',
    'author' => 'Create Starter',
    
    'providers' => [
        // Modules\Log\Providers\LogServiceProvider::class,
    ],
    
    'aliases' => [
        // 'Log' => Modules\Log\Facades\LogFacade::class,
    ],
    
    'routes' => [
        'web' => 'Routes/web.php',
        'admin' => 'Routes/admin.php',
    ],
    
    'middleware' => [
        'web' => ['auth', 'verified'],
        'admin' => ['auth', 'admin'],
    ],
    
    'views' => [
        'web' => 'Resources/Views/Web',
        'admin' => 'Resources/Views/Admin',
    ],
    
    'settings' => [
        'activity_log' => [
            'enabled' => true,
            'log_models' => true,
            'log_auth' => true,
            'cleanup_days' => 30,
        ],
    ],
]; 