<?php

use Illuminate\Support\Facades\Route;
use Modules\Log\Controllers\Admin\ActivityLogController;

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    
    // Activity Log Routes
    Route::prefix('logs')->name('admin.logs.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
    });
    
}); 