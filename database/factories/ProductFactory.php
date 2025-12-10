<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    use HasFactory;

    public function definition(): array
    {
        $title = $this->faker->unique()->words(rand(1, 3), true);
        $slug = Str::slug($title, '-');

        return compact('title', 'slug');
    }
}
