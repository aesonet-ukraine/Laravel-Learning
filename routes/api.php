<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->middleware('access-token')->group(function () {
    Route::post('register', [AuthController::class, 'register'])
        ->name('register');
    Route::post('login', [AuthController::class, 'login'])
        ->name('login');
});
