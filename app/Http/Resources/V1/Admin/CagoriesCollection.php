<?php

namespace App\Http\Resources\V1\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @mixin Category
 */
class CagoriesCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            //            'id' => $this->getKey(),
            //            'title' => $this->title,
            //            'slug' => $this->slug,
            //            'parent_id' => $this->parent_id,
            //            'is_deleted' => $this->isDeleted(),
            //            'created_at' => $this->created_at,
            //            'updated_at' => $this->updated_at,
        ];
    }
}
