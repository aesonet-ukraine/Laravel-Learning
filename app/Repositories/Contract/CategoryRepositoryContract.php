<?php

namespace App\Repositories\Contract;

use App\Http\Requests\PaginationRequest;
use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryRepositoryContract
{
    /**
     * @return LengthAwarePaginator<int, Category>
     */
    public function paginate(PaginationRequest $request): LengthAwarePaginator;
}
