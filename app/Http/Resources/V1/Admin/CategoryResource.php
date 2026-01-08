<?php

namespace App\Http\Resources\V1\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

/**
 * @mixin Category
 */
class CategoryResource extends JsonResource
{
    public function __construct(Category|MissingValue|null $resource = null)
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
            'parent_id' => $this->parent_id,
            'thumbnail' => $this->thumbnail,
            'parent' => self::make($this->whenLoaded('parent')),
            //            'children'=> new CagoriesCollection($this->whenLoaded('children')),
            'products_count' => $this->products_count ?? 0,
            'is_deleted' => $this->resource->is_deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
