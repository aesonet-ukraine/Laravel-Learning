<?php

namespace App\Models;

use App\Service\Contract\FileServiceContract;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'price',
        'slug',
        'SKU',
        'discount',
        'quantity',
        'thumbnail',
    ];

    protected $casts = [
        'price' => 'float',
        'discount' => 'integer',
        'quantity' => 'integer',
    ];

    /*
     * @return BelongsToMany<Category>
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /*
     * @return BelongsToMany<Order>
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class);
    }

    public function setThumbnailAttribute(UploadedFile|string $file): void
    {
        if (is_string($file)) {
            $this->attributes['thumbnail'] = $file;
        } else {
            if (! empty($this->attributes['thumbnail'])) {
                Storage::delete($this->attributes['thumbnail']);
            }
            $this->attributes['thumbnail'] = app(FileServiceContract::class)->upload(
                $file,
                'products/'.$this->attributes['slug'],
            );
        }
    }

    public function thumbnailUrl(): Attribute
    {
        return Attribute::get(fn () => url(Storage::url($this->getAttribute('thumbnail'))));
    }

    public function finalPrice(): Attribute
    {
        return Attribute::get(
            fn () => round(
                $this->attributes['price'] - ($this->attributes['price'] * $this->attributes['discount'] / 100),
                2
            )
        );
    }
}
