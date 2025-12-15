<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->unique()->words(rand(1, 3), true);
        $slug = Str::slug($title, '-');

        return [
            'title' => $title,
            'slug' => $slug,
            'SKU' => $this->faker->unique()->ean13(),
            'description' => $this->faker->sentence(rand(10, 20)),
            'price' => $this->faker->randomFloat(2, 1, 200),
            'discount' => $this->faker->boolean() ? rand(10, 65) : null,
            'quantity' => $this->faker->numberBetween(0, 50),
            'thumbnail' => $this->faker->imageUrl(),
        ];
    }

    public function withTitle(string $title): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => $title,
            'slug' => Str::slug($title, '-'),
        ]);
    }
}
