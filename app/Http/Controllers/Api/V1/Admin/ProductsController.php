<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Enums\Permissions\ProductEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\CreateRequest;
use App\Http\Requests\Admin\Products\UpdateRequest;
use App\Http\Requests\PaginationRequest;
use App\Http\Resources\V1\Admin\ProductResource;
use App\Models\Product;
use App\Repositories\Contract\ProductRepositoryContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Subgroup;
use Throwable;

#[Group('Admin', 'Admin API endpoints')]
#[Subgroup('Products', <<<TEXT
CRUD Products endpoints. \n
<strong>Warning!</strong> All requests allows only for the next roles: ADMIN, MODERATOR.
TEXT)]
#[Header('access-token', 'API ACCESS TOKEN')]
#[Authenticated]
class ProductsController extends Controller
{
    #[Endpoint('List of Products', 'Load the list of Products')]
    public function index(PaginationRequest $request, ProductRepositoryContract $repository): AnonymousResourceCollection
    {
        $Products = $repository->paginate($request);

        return ProductResource::collection($Products);
    }

    #[Endpoint('Store Product', 'Allow to create a Product')]
    public function store(CreateRequest $request, ProductRepositoryContract $repository)
    {
        if ($product = $repository->store($request)) {
            return ProductResource::make($product);
        }

        return response()->json([
            'data' => [
                'message' => 'Product creation failed',
            ],
        ], 422);

    }

    #[Endpoint('Show Product', 'Show specified Product by id')]
    public function show(Product $Product): ProductResource
    {
        $Product->load(['parent', 'children'])->loadCount('products');

        return new ProductResource($Product);
    }

    #[Endpoint('Update Product', 'Update specified Product by id')]
    public function update(UpdateRequest $request, Product $product, ProductRepositoryContract $repository): ProductResource|JsonResponse
    {
        if ($repository->update($request, $product)) {
            $product->refresh();

            return ProductResource::make($product);
        }

        return response()->json([
            'data' => [
                'message' => 'Product update failed',
            ],
        ], 422);
    }

    #[Endpoint('Delete Product', 'Delete specified Product by id')]
    public function destroy(Product $Product): ProductResource|JsonResponse
    {
        try {
            $this->middleware('permission:'.ProductEnum::DELETE_PRODUCTS->value);

            $Product->delete();

            return new ProductResource($Product->refresh());
        } catch (Throwable $throwable) {
            logs()->error('[ProductController::destroy]: '.$throwable->getMessage(), [
                'Product_id' => $Product->id,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'data' => [
                    'message' => $throwable->getMessage(),
                ],
            ], 422);
        }
    }

    #[Endpoint('Restore Product', 'Restore specified deleted Product by id')]
    public function restore(Product $Product): ProductResource|JsonResponse
    {
        try {
            $Product->restore();

            return new ProductResource($Product->refresh());
        } catch (Throwable $throwable) {
            logs()->error('[ProductController::destroy]: '.$throwable->getMessage(), [
                'Product_id' => $Product->id,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'data' => [
                    'message' => $throwable->getMessage(),
                ],
            ], 422);
        }
    }

    #[Endpoint('Force delete Product', 'Permanently delete specified Product by id')]
    public function forceDelete(Product $Product): ProductResource|JsonResponse
    {
        try {
            $Product->forceDelete();

            return new ProductResource($Product->refresh());
        } catch (Throwable $throwable) {
            logs()->error('[ProductController::destroy]: '.$throwable->getMessage(), [
                'Product_id' => $Product->id,
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
