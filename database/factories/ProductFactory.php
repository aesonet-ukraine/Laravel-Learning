<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;

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
            'thumbnail' => $this->generateThumbnail($slug),
        ];
    }

    public function withTitle(string $title): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => $title,
            'slug' => Str::slug($title, '-'),
        ]);
    }
    protected function generateThumbnail(string $slug): string
    {
        $dirName = 'faker/products/' . $slug;

        $faker = \Faker\Factory::create();
        $faker->addProvider(new FakerPicsumImagesProvider($faker));
        if(! Storage::exists($dirName)) {
            Storage::createDirectory($dirName);
        }
        /*
         * @var FakerPicsumImagesProvider $faker
         */
        return $dirName . '/' . $faker->image(
                dir: Storage::path($dirName),
                fullPath: false,
            );
    }
}
