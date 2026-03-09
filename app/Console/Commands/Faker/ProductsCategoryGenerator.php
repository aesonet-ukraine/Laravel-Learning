<?php

namespace App\Console\Commands\Faker;

use App\Faker\ProductProvider;
use App\Jobs\Faker\GenerateProductImageJob;
use App\Models\Category;
use App\Models\Product;
use Faker\Generator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class ProductsCategoryGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'faker:generate-products-categories
        {--count=100 : How many products will be created}
        {--chunk=500 : Insert chunk size for performance}
        {--category= : Force single category key (e.g. electronics) (See: ProductProvider::class)}
        {--truncate= : Truncate the products table}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates products categories with more realistic titles';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $count = (int) $this->option('count');
        $chunk = (int) $this->option('chunk');
        $forcedCategory = $this->option('category');

        if ($count <= 0 || $chunk <= 0) {
            $this->error('--count|--chunk should be more than 0');

            return static::FAILURE;
        }
        /**
         * @var Generator&ProductProvider $faker
         */
        $faker = app(Generator::class);
        $faker->addProvider(new ProductProvider($faker));

        if ($this->option('truncate')) {
            $this->warn('Truncating products table');
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            DB::table('category_product')->truncate();
            DB::table('products')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }

        $this->info("Generating $count products...");

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $remaining = $count;
        $now = now();
        try {
            while ($remaining > 0) {
                $batchSize = min($chunk, $remaining);
                $rows = [];
                $sync = [];

                for ($i = 0; $i < $batchSize; $i++) {
                    $categoryData = $forcedCategory
                        ? $faker->getCategoryByKey((string) $forcedCategory)
                        : $faker->productCategory();

                    $category = Cache::remember(
                        "cat_faker_$categoryData[key]",
                        300,
                        fn () => Category::firstOrCreate(['slug' => $categoryData['key']], [
                            'title' => $categoryData['label'],
                            'slug' => $categoryData['key'],
                        ])
                    );

                    $productTitle = $faker->productName($category->slug);
                    $slug = Str::slug($productTitle, '-');

                    $product = [
                        'title' => $productTitle,
                        'slug' => $slug,
                        'SKU' => $faker->unique()->ean13(),
                        'description' => $faker->sentences(rand(5, 10), true),
                        'price' => $faker->randomFloat(2, 10, 200),
                        'discount' => $faker->boolean() ? rand(10, 65) : null,
                        'quantity' => $faker->numberBetween(0, 50),
                        'thumbnail' => 'placeholder.png',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    $rows[] = $product;
                    $sync[$category->id][] = $slug;

                    //                        $this->info("Product '$productTitle' added to [$category->slug]");
                    //                        $this->newLine();
                }

                Product::query()->fillAndInsertOrIgnore($rows);
                $this->syncCategoriesWithProducts($sync);

                $bar->advance($batchSize);
                $remaining -= $batchSize;
            }

            $bar->finish();
            $this->newLine();
            $this->info('Done ✅');

            return static::SUCCESS;
        } catch (Throwable $throwable) {
            $this->error($throwable->getMessage());

            logs()->error('[ProductsCategoriesGenerator] '.$throwable->getMessage(), [
                'details' => $throwable,
            ]);

            return static::FAILURE;
        }

    }

    protected function syncCategoriesWithProducts(array $sync): void
    {
        Category::query()
            ->whereIn('id', array_keys($sync))
            ->get()
            ->map(function (Category $category) use ($sync) {
                $ids = Product::query()
                    ->select('id')
                    ->whereIn('slug', $sync[$category->id])
                    ->pluck('id')
                    ->toArray();

                $category->products()->syncWithoutDetaching($ids);

                foreach ($ids as $id) {
                    GenerateProductImageJob::dispatch($id)
                        ->delay(now()->addSeconds(5));
                }
            });
    }
}
