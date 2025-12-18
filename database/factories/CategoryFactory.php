<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    use HasFactory;

    public function definition(): array
    {
        $title = $this->faker->unique()->words(rand(1, 3), true);
        $slug = Str::slug($title, '-');

        return compact('title', 'slug');
    }


    public function withParent(): static
    {
        return $this->state(fn (array $attributes) =>[
            'parent_id' => Category::all()->random()?->id,
        ]);
    }
}
