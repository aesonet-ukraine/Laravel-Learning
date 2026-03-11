<?php

use App\Enums\Permissions\CategoryEnum;
use App\Enums\Permissions\ProductEnum;
use App\Enums\RolesEnum;
use App\Http\Controllers\Api\V1\Admin\CategoriesController;
use App\Http\Controllers\Api\V1\Admin\ProductsController;
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
        ->middleware('permission:'.CategoryEnum::DELETE_CATEGORY->value)
        ->withTrashed();

    Route::delete('categories/{category}/force-delete', [CategoriesController::class, 'forceDelete'])
        ->name('categories.force-delete')
        ->middleware('role:'.RolesEnum::ADMIN->value)
        ->withTrashed();

    Route::apiResource('products', ProductsController::class)->withTrashed();

    Route::delete('products/{product}', [ProductsController::class, 'destroy'])
        ->name('products.delete')
        ->middleware('permission:'.ProductEnum::DELETE_PRODUCTS->value)
        ->withTrashed();

    Route::put('products/{product}/restore', [ProductsController::class, 'restore'])
        ->name('products.restore')
        ->middleware('permission:'.ProductEnum::DELETE_PRODUCTS->value)
        ->withTrashed();

    Route::delete('products/{product}/force-delete', [ProductsController::class, 'forceDelete'])
        ->name('products.force-delete')
        ->middleware('role:'.RolesEnum::ADMIN->value)
        ->withTrashed();
});
