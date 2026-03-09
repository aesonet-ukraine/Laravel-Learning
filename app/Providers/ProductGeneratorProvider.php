<?php

namespace App\Providers;

use App\Faker\ProductProvider;
use Faker\Generator;
use Illuminate\Support\ServiceProvider;

class ProductGeneratorProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->extend(Generator::class, function (Generator $generator) {
            $generator->addProvider(new ProductProvider($generator));

            return $generator;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    public function provides(): array
    {
        return [Generator::class];
    }
}
