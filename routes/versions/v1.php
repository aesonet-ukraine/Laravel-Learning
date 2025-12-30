<?php

use App\Enums\RolesEnum;
use App\Http\Controllers\Api\V1\Admin\CategoriesController;
use Spatie\Permission\Middleware\RoleMiddleware;

Route::name('admin.')->prefix('admin')->middleware([
    'auth:sanctum',
    RoleMiddleware::class.':'.implode('|', [RolesEnum::ADMIN->value, RolesEnum::MODERATOR->value]),
])->group(function () {
    Route::apiResource('categories', CategoriesController::class);
});
