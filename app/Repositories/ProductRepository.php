<?php

namespace App\Repositories;

use App\Http\Requests\Admin\Products\CreateRequest;
use App\Http\Requests\Admin\Products\UpdateRequest;
use App\Http\Requests\PaginationRequest;
use App\Models\Product;
use App\Repositories\Contract\ProductRepositoryContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class ProductRepository extends Repository implements ProductRepositoryContract
{
    /**
     * @return LengthAwarePaginator<int, Product>
     */
    public function paginate(PaginationRequest $request): LengthAwarePaginator
    {
        $this->setData($request->validated());

        $query = Product::query()
            ->with(['categories']);

        $this->trashed($query);
        $this->applySorting($query);
        $this->applyFilters($query);

        return $query->paginate(
            $request->get('per_page', 15),
            page: $request->get('page', 1),
        );
    }

    public function store(CreateRequest $request): Product|false
    {
        try {
            DB::beginTransaction();

            $data = $this->formRequestData($request->validated());
            $product = Product::create($data['attributes']);
            $product->categories()->sync($data['categories']);

            DB::commit();

            return $product;
        } catch (Throwable $throwable) {
            DB::rollBack();

            logs()->error('[ProductRepository::store] '.$throwable->getMessage(), [
                'fields' => $request->validated(),
                'user_id' => auth()->id(),
            ]);

            return false;
        }
    }

    public function update(UpdateRequest $request, Product $product): bool
    {
        try {
            DB::beginTransaction();

            $data = $this->formRequestData($request->validated());
            $product->update($data['attributes']);
            $product->categories()->sync($data['categories']);

            DB::commit();

            return true;
        } catch (Throwable $throwable) {
            DB::rollBack();

            logs()->error('[ProductRepository::update] '.$throwable->getMessage(), [
                'fields' => $request->validated(),
                'product_id' => $product->id,
                'user_id' => auth()->id(),
            ]);

            return false;
        }
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function formRequestData(array $data): array
    {
        $attributes = Arr::except($data, ['categories', 'images']);
        $attributes = Arr::prepend($attributes,
            Str::slug($data['title']),
            'slug',
        );

        return [
            'attributes' => $attributes,
            'categories' => $data['categories'] ?? [],
            'images' => $data['images'] ?? [],
        ];
    }
}
