<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\Contract\CategoryRepositoryContract;
use App\Repositories\Contract\ProductRepositoryContract;
use App\Repositories\ProductRepository;
use App\Service\Contract\FileServiceContract;
use App\Service\FileService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public $bindings = [
        CategoryRepositoryContract::class => CategoryRepository::class,
        ProductRepositoryContract::class => ProductRepository::class,
        FileServiceContract::class => FileService::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
