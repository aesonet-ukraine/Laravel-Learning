<?php

namespace App\Jobs\Faker;

use App\Enums\QueueEnum;
use App\Models\Product;
use Faker\Generator;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class GenerateProductImageJob implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public function __construct(protected readonly int $productId)
    {
        $this->onQueue(QueueEnum::FAKER->value);
    }

    public function uniqueId(): string
    {
        return "faker:image_generation_$this->productId";
    }

    public function handle(): void
    {
        try {
            $product = Product::query()
                ->where('id', $this->productId)
                ->where('thumbnail', 'placeholder.png')
                ->first();

            if (! $product) {
                return;
            }

            $faker = app(Generator::class);

            $product->update([
                'thumbnail' => $faker->generateThumbnail($product->slug),
            ]);
        } catch (Throwable $th) {

            logs()->error('[GenerateProductImageJob]: '.$th->getMessage(), [
                'exception' => $th,
                'product_id' => $this->productId,
            ]);
        }
    }
}
