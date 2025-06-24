<?php

// Otomatik modül route yükleme sistemi
// Tüm modüllerin web ve admin route dosyalarını otomatik yükler

// Web routes yükleme
foreach (glob(base_path('Modules/*/Routes/web.php')) as $routeFile) {
    require $routeFile;
}

// Admin routes yükleme
foreach (glob(base_path('Modules/*/Routes/admin.php')) as $routeFile) {
    require $routeFile;
} 