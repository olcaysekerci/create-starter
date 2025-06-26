<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Controllers\Admin\UserManagementController;

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return inertia('Admin/Dashboard');
    })->name('dashboard');
    
    // User Management
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
}); 