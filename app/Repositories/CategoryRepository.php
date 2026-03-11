<?php

namespace App\Repositories;

use App\Http\Requests\PaginationRequest;
use App\Models\Category;
use App\Repositories\Contract\CategoryRepositoryContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @template TModel of Model
 */
class CategoryRepository extends Repository implements CategoryRepositoryContract
{
    /**
     * @return LengthAwarePaginator<int, Category>
     */
    public function paginate(PaginationRequest $request): LengthAwarePaginator
    {
        $this->setData($request->validated());

        $query = Category::query()
            ->with(['parent', 'children'])
            ->withCount('products');

        $this->trashed($query);
        $this->applySorting($query);
        $this->applyFilters($query);

        return $query->paginate(
            $request->get('per_page', 15),
            page: $request->get('page', 1),
        );
    }

    /**
     * @param  Builder<TModel>  $query
     */
    protected function applyFilters(Builder $query): void
    {
        if (! empty($this->data['filters'])) {
            foreach ($this->data['filters'] as $filter) {
                [$key, $operator, $value] = $this->destructFilters($filter);

                switch ($key) {
                    case 'parent':
                        if ($value === '1') {
                            $query->whereNotNull('parent_id');
                        } else {
                            $query->whereNull('parent_id');
                        }
                        break;
                    case 'children':
                        if ($value === '1') {
                            $query->has('children');
                        } else {
                            $query->doesntHave('children');
                        }
                        break;
                    case 'product_count':
                        $query->has('products', $operator, $value);
                        break;
                    default: $query->where($key, $operator, $value);
                }
            }
        }
    }
}
