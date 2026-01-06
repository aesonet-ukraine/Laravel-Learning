<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Repositories\Contract\CategoryRepositoryContract;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public $bindings = [
        CategoryRepositoryContract::class => CategoryRepository::class,
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
