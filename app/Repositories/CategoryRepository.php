<?php

namespace App\Repositories;

use App\Http\Requests\PaginationRequest;
use App\Models\Category;
use App\Repositories\Contract\CategoryRepositoryContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository implements CategoryRepositoryContract
{
    /**
     * @return LengthAwarePaginator<int, Category>
     */
    public function paginate(PaginationRequest $request): LengthAwarePaginator
    {
        $data = $request->validated();

        $query = Category::query()
            ->when(isset($data['trashed']) && $data['trashed'],
                fn ($query) => $query->withTrashed()
                    ->whereNotNull('deleted_at')
            )
            ->with(['parent', 'children'])
            ->withCount('products');
        if (! empty($data['filters'])) {
            $this->applyFilters($query, $data['filters']);
        }
        if (! empty($data['sort'])) {
            $this->applySorting($query, $data['sort']);
        }

        return $query->paginate(
            $request->get('per_page', 15),
            page: $request->get('page', 1),
        );
    }

    /**
     * @param  Builder<Category>  $query
     * @param  array<string, mixed>  $filters
     */
    private function applyFilters(Builder $query, array $filters): void
    {
        foreach ($filters as $filter) {
            if (! array_key_exists('key', $filters)) {
                continue;
            }

            switch ($filter['key']) {
                case 'parent':
                    if ($filter['value'] === '1') {
                        $query->whereNotNull('parent_id');
                    } else {
                        $query->whereNull('parent_id');
                    }
                    break;
                case 'children':
                    if ($filter['value'] === '1') {
                        $query->has('children');
                    } else {
                        $query->doesntHave('children');
                    }
                    break;
                case 'product_count':
                    $query->has('products', $filter['operator'], $filter['value']);
                    break;
                default: $query->where($filter['key'], $filter['operator'], $filter['value']);
            }
        }
    }

    /**
     * @param  Builder<Category>  $query
     * @param  array<int, array{key: string, direction: string}>  $sorts
     */
    private function applySorting(Builder $query, array $sorts): void
    {
        foreach ($sorts as $sort) {
            $query->orderBy($sort['key'], $sort['direction']);
        }
    }
}
