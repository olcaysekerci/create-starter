<?php

use Illuminate\Support\Facades\Route;
use Modules\MailNotification\Controllers\Admin\MailNotificationController;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::prefix('mail-notifications')->name('mail-notifications.')->group(function () {
        Route::get('/', [MailNotificationController::class, 'index'])->name('index');
        Route::get('/{id}', [MailNotificationController::class, 'show'])->name('show');
        Route::post('/test', [MailNotificationController::class, 'test'])->name('test');
        Route::post('/retry', [MailNotificationController::class, 'retry'])->name('retry');
        Route::post('/cleanup', [MailNotificationController::class, 'cleanup'])->name('cleanup');
    });
}); 