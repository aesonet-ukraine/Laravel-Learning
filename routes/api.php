<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function () {
    Route::post('auth/register', [AuthController::class, 'register'])
        ->name('register');
    Route::post('auth/login', [AuthController::class, 'login'])
        ->name('login');
});
