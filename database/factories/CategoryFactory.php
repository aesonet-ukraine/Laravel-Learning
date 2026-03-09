<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $title = Str::limit($this->faker->unique()->words(rand(1, 3), true), 255);
        $slug = Str::slug($title, '-');

        return [
            'title' => $title,
            'slug' => $slug,
            'parent_id' => null,
            'thumbnail' => $this->generateThumbnail($slug),
        ];
    }

    protected function generateThumbnail(string $slug): string
    {
        $dirName = 'faker/categories/'.$slug;

        $faker = \Faker\Factory::create();
        $faker->addProvider(new FakerPicsumImagesProvider($faker));
        if (! Storage::disk('public')->exists($dirName)) {
            Storage::disk('public')->makeDirectory($dirName);
        }

        /*
         * @var FakerPicsumImagesProvider $faker
         */
        return $dirName.'/'.$faker->image(
            dir: Storage::disk('public')->path($dirName),
            isFullPath: false,
        );

    }

    public function withParent(): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => Category::query()->inRandomOrder()->value('id') ?: null,
        ]);
    }
}
