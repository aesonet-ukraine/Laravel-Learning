<?php

namespace App\Http\Resources\V1\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

/**
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    public function __construct(Product|MissingValue|null $resource = null)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'title' => $this->title,
            'slug' => $this->slug,
            'SKU' => $this->SKU,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'price_block' => [
                'price' => $this->price,
                'discount' => $this->discount,
                'final_price' => $this->finalPrice,
            ],
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'thumbnail' => $this->thumbnailUrl,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
