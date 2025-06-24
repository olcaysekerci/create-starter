<?php

return [
    'name' => 'User',
    'description' => 'Kullanıcı yönetimi modülü - kayıt, giriş, profil yönetimi, admin paneli',
    'version' => '1.0.0',
    'author' => 'Create Starter',
    
    'providers' => [
        // Modules\User\Providers\UserServiceProvider::class,
    ],
    
    'aliases' => [
        // 'User' => Modules\User\Facades\UserFacade::class,
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
]; 