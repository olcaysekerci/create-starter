<?php

use Illuminate\Support\Facades\Route;
use Modules\Settings\Controllers\Admin\SettingsController;

Route::prefix('admin/settings')->name('admin.settings.')->middleware(['auth', 'verified'])->group(function () {
    
    // Ana ayarlar sayfası
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    
    // Mail ayarları
    Route::prefix('mail')->name('mail.')->group(function () {
        Route::get('/', [SettingsController::class, 'mail'])->name('index');
        Route::post('/', [SettingsController::class, 'updateMail'])->name('update');
        Route::post('/test', [SettingsController::class, 'testMail'])->name('test');
    });
    
    // Uygulama ayarları
    Route::prefix('app')->name('app.')->group(function () {
        Route::get('/', [SettingsController::class, 'app'])->name('index');
        Route::post('/', [SettingsController::class, 'updateApp'])->name('update');
    });
    
    // Sistem ayarları
    Route::prefix('system')->name('system.')->group(function () {
        Route::get('/', [SettingsController::class, 'system'])->name('index');
        Route::post('/', [SettingsController::class, 'updateSystem'])->name('update');
    });
}); 