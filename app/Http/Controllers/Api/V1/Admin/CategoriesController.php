<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Enums\Permissions\CategoryEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Categories\CreateRequest;
use App\Http\Requests\Admin\Categories\UpdateRequest;
use App\Http\Requests\PaginationRequest;
use App\Http\Resources\V1\Admin\CategoryResource;
use App\Models\Category;
use App\Repositories\Contract\CategoryRepositoryContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Subgroup;
use Throwable;

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
    public function index(PaginationRequest $request, CategoryRepositoryContract $repository): AnonymousResourceCollection
    {
        $categories = $repository->paginate($request);

        return CategoryResource::collection($categories);
    }

    #[Endpoint('Store category', 'Allow to create a category')]
    public function store(CreateRequest $request): CategoryResource|JsonResponse
    {
        try {
            $category = Category::create([
                ...$request->validated(),
                'slug' => Str::slug($request->get('title')),
            ]);

            return new CategoryResource($category);
        } catch (Throwable $throwable) {
            logs()->error('[CategoryController::update]: '.$throwable->getMessage(), [
                'fields' => $request->all(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'data' => [
                    'message' => $throwable->getMessage(),
                ],
            ], 422);
        }
    }

    #[Endpoint('Show category', 'Show specified category by id')]
    public function show(Category $category): CategoryResource
    {
        $category->load(['parent', 'children'])->loadCount('products');

        return new CategoryResource($category);
    }

    #[Endpoint('Update category', 'Update specified category by id')]
    public function update(UpdateRequest $request, Category $category): CategoryResource|JsonResponse
    {
        try {
            $fields = $request->validated();

            if (! empty($fields['title']) && $fields['title'] !== $category->title) {
                $fields['slug'] = Str::slug($fields['title']);
            }

            $category->update($fields);

            return new CategoryResource($category);
        } catch (Throwable $throwable) {
            logs()->error('[CategoryController::update]: '.$throwable->getMessage(), [
                'category_id' => $category->id,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'data' => [
                    'message' => $throwable->getMessage(),
                ],
            ], 422);
        }
    }

    #[Endpoint('Delete category', 'Delete specified category by id')]
    public function destroy(Category $category): CategoryResource|JsonResponse
    {
        try {
            $this->middleware('permission:'.CategoryEnum::DELETE_CATEGORY->value);

            $category->delete();

            return new CategoryResource($category->refresh());
        } catch (Throwable $throwable) {
            logs()->error('[CategoryController::destroy]: '.$throwable->getMessage(), [
                'category_id' => $category->id,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'data' => [
                    'message' => $throwable->getMessage(),
                ],
            ], 422);
        }
    }

    #[Endpoint('Restore category', 'Restore specified deleted category by id')]
    public function restore(Category $category): CategoryResource|JsonResponse
    {
        try {
            $category->restore();

            return new CategoryResource($category->refresh());
        } catch (Throwable $throwable) {
            logs()->error('[CategoryController::destroy]: '.$throwable->getMessage(), [
                'category_id' => $category->id,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'data' => [
                    'message' => $throwable->getMessage(),
                ],
            ], 422);
        }
    }

    #[Endpoint('Force delete category', 'Permanently delete specified category by id')]
    public function forceDelete(Category $category): CategoryResource|JsonResponse
    {
        try {
            $category->forceDelete();

            return new CategoryResource($category->refresh());
        } catch (Throwable $throwable) {
            logs()->error('[CategoryController::destroy]: '.$throwable->getMessage(), [
                'category_id' => $category->id,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'data' => [
                    'message' => $throwable->getMessage(),
                ],
            ], 422);
        }
    }
}
