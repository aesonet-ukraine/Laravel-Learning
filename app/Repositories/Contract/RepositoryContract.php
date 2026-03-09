<?php

namespace App\Repositories\Contract;

use App\Http\Requests\PaginationRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryContract
{
    /**
     * @return LengthAwarePaginator<int, Model>
     */
    public function paginate(PaginationRequest $request): LengthAwarePaginator;
}
