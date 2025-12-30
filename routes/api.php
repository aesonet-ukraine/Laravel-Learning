<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::prefix('auth')
        ->middleware('access-token')
        ->name('auth.')
        ->group(function () {
            Route::post('register', [AuthController::class, 'register'])
                ->name('register');
            Route::post('login', [AuthController::class, 'login'])
                ->name('login');
        });
    Route::name('v1.')
        ->prefix('v1')
        ->group(function () {
            require __DIR__.'/versions/v1.php';
        });
});
