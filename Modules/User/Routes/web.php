<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Controllers\Web\UserProfileController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/user/profile', [UserProfileController::class, 'show'])->name('user.profile.show');
    Route::put('/user/profile', [UserProfileController::class, 'update'])->name('user.profile.update');
}); 