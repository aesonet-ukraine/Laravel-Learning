<?php

namespace App\Repositories\Contract;

use App\Http\Requests\Admin\Products\CreateRequest;
use App\Http\Requests\Admin\Products\UpdateRequest;
use App\Http\Requests\PaginationRequest;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryContract
{
    /**
     * @return LengthAwarePaginator<int, Product>
     */
    public function paginate(PaginationRequest $request): LengthAwarePaginator;

    public function store(CreateRequest $request): Product|false;

    public function update(UpdateRequest $request, Product $product): bool;
}
