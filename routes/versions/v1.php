<?php

use App\Enums\Permissions\CategoryEnum;
use App\Enums\RolesEnum;
use App\Http\Controllers\Api\V1\Admin\CategoriesController;
use Spatie\Permission\Middleware\RoleMiddleware;

Route::name('admin.')->prefix('admin')->middleware([
    'auth:sanctum',
    RoleMiddleware::class.':'.implode('|', [RolesEnum::ADMIN->value, RolesEnum::MODERATOR->value]),
])->group(callback: function () {
    Route::apiResource('categories', CategoriesController::class)->withTrashed();

    Route::delete('categories/{category}', [CategoriesController::class, 'destroy'])
        ->name('categories.delete')
        ->middleware('permission:'.CategoryEnum::DELETE_CATEGORY->value)
        ->withTrashed();
    Route::put('categories/{category}/restore', [CategoriesController::class, 'restore'])
        ->name('categories.restore')
        ->middleware('permission'.CategoryEnum::DELETE_CATEGORY->value)
        ->withTrashed();
    Route::delete('categories/{category}/force-delete', [CategoriesController::class, 'forceDelete'])
        ->name('categories.force-delete')
        ->middleware('permission'.RolesEnum::ADMIN->value)
        ->withTrashed();
});
