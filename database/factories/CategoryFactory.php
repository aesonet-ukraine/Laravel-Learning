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
        $title = $this->faker->unique()->words(rand(1, 3), true);
        $slug = Str::slug($title, '-');

        return [
            'title' => $title,
            'slug' => $slug,
            'parent_id' => $this->withParent(),
            'thumbnail' => $this->generateThumbnail($slug),
        ];
    }

    protected function generateThumbnail(string $slug): string
    {
        $dirName = 'faker/categories/'.$slug;

        $faker = \Faker\Factory::create();
        $faker->addProvider(new FakerPicsumImagesProvider($faker));
        if (! Storage::exists($dirName)) {
            Storage::makeDirectory($dirName);
        }

        /*
         * @var FakerPicsumImagesProvider $faker
         */
        return $dirName.'/'.$faker->image(
            dir: Storage::path($dirName),
        );

    }

    public function withParent(): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => Category::query()->inRandomOrder()->value('id') ?: null,
        ]);
    }
}
