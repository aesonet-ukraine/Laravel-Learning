<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Categories\CreateRequest;
use App\Http\Requests\Admin\Categories\UpdateRequest;
use App\Http\Resources\V1\Admin\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group('Admin', 'Admin API endpoints')]
#[Subgroup('Categories', <<<TEXT
CRUD categories endpoints. \n
<strong>Warning!</strong> All requests allows only for the next roles: ADMIN, MODERATOR.
TEXT)]
#[Header('access-token', 'API ACCESS TOKEN')]
#[Authenticated]
class CategoriesController extends Controller
{
    #[Endpoint('List of categories', 'Load the list of categories')]
    public function index(): AnonymousResourceCollection
    {
        $categories = Category::with(['parent', 'children'])
            ->withCount('products')
            ->get();

        return CategoryResource::collection($categories);
    }

    #[Endpoint('Store category', 'Allow to create a category')]
    public function store(CreateRequest $request): JsonResponse
    {
        return response()->json($request->validated());
    }

    #[Endpoint('Show category', 'Show specified category by id')]
    public function show(Category $category): void
    {
        //
    }

    #[Endpoint('Update category', 'Update specified category by id')]
    public function update(UpdateRequest $request, Category $category): void
    {
        //
    }

    #[Endpoint('Delete category', 'Delete specified category by id')]
    public function destroy(Category $category): void
    {
        //
    }
}
