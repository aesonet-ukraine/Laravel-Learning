<?php

namespace Http\Controllers\Api;

use App\Enums\RolesEnum;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\FeatureTestCase;

class ProductsControllerTest extends FeatureTestCase
{
    /**
     * A basic feature test example.
     */
    #[Test]
    public function it_created_product_successful(): void
    {

        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->syncAdminRole()
            ->create();

        $this->actingAs($user, 'sanctum');

        $product = Product::factory()
            ->makeOne();

        $response = $this->request()
            ->post(
                route('api.v1.admin.products.store'),
                [
                    ...$product->toArray(),
                    //                   LogicException: GD extension is not installed.
                    //                   'thumbnail' => UploadedFile::fake()->image('thumbnail.jpg'),
                    'thumbnail' => new File(
                        Storage::disk('public')->path('static/thumbnail.jpg')
                    ),
                ],
            );

        $response->assertStatus(201);
        $response
            ->assertJson(fn (AssertableJson $json) => $json->has('data.id')
                ->where('data.title', $product->title)
                ->where('data.slug', $product->slug)
                ->whereNull('data.deleted_at')
                ->etc()
            );
    }

    #[Test]
    public function is_updated_product_successful_by_validation(): void
    {
        $title = 'Updated Product Title';
        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->syncAdminRole()
            ->create();
        $this->actingAs($user, 'sanctum');

        $product = Product::factory()
            ->createOne();
        $response = $this->request()
            ->put(
                route('api.v1.admin.products.update', [
                    'product' => $product->id,
                ]), [
                    ...$product->toArray(),
                    'thumbnail' => new File(
                        Storage::disk('public')->path('static/thumbnail.jpg')
                    ),
                    'title' => $title,
                ]
            );
        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json->has('data.id')
                ->where('data.title', $title)
                ->etc()
            );

    }

    #[Test]
    public function it_deleted_product_successful(): void
    {
        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->syncAdminRole()
            ->create();
        $this->actingAs($user, 'sanctum');

        $product = Product::factory()
            ->createOne();
        $response = $this->request()
            ->deleteJson(
                route('api.v1.admin.products.delete', [
                    'product' => $product->id,
                ])
            );
        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json->has('data.id')
                ->where('data.id', $product->id)
                ->etc()
            );
    }

    #[Test]
    #[DataProvider('failedProductPermission')]
    public function it_not_deleted_product_successful(
        RolesEnum $role,
    ): void {
        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->syncCustomRole($role)
            ->create();
        $this->actingAs($user, 'sanctum');

        $product = Product::factory()
            ->createOne();
        $response = $this->request()
            ->deleteJson(
                route('api.v1.admin.products.delete', [
                    'product' => $product->id,
                ])
            );
        $response->assertStatus(403)
            ->assertJson(fn (AssertableJson $json) => $json->where(
                'message',
                'User does not have the right roles.')
                ->etc()
            );

    }

    #[Test]
    public function it_restore_deleted_product_successful(): void
    {
        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->syncAdminRole()
            ->create();
        $this->actingAs($user, 'sanctum');

        $product = Product::factory()
            ->createOne();
        $this->request()
            ->deleteJson(
                route('api.v1.admin.products.delete', [
                    'product' => $product->id,
                ])
            );
        $response = $this->request()
            ->putJson(
                route('api.v1.admin.products.restore', [
                    'product' => $product->id,
                ])
            );
        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json->has('data.id')
                ->where('data.id', $product->id)
                ->etc()
            );
    }

    #[Test]
    #[DataProvider('failedProductPermission')]
    public function it_not_restore_deleted_product_successful(
        RolesEnum $role,
    ): void {
        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->syncCustomRole($role)
            ->create();
        $this->actingAs($user, 'sanctum');

        $product = Product::factory()
            ->createOne();

        $this->request()
            ->deleteJson(
                route('api.v1.admin.products.delete', [
                    'product' => $product->id,
                ])
            );
        $response = $this->request()
            ->putJson(
                route('api.v1.admin.products.restore', [
                    'product' => $product->id,
                ])
            );

        $response->assertStatus(403)
            ->assertJson(fn (AssertableJson $json) => $json->where(
                'message',
                'User does not have the right roles.')
                ->etc()
            );

    }

    #[Test]
    public function it_force_deleted_product_successful(): void
    {
        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->syncAdminRole()
            ->create();
        $this->actingAs($user, 'sanctum');
        $product = Product::factory()
            ->createOne();
        $response = $this->request()
            ->deleteJson(
                route('api.v1.admin.products.force-delete', [
                    'product' => $product->id,
                ])
            );

        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json->has('data.id')
                ->where('data.id', $product->id)
                ->etc()
            );
    }

    #[Test]
    #[DataProvider('failedProductPermission')]
    public function it_force_not_deleted_product_successful(
        RolesEnum $role,
    ): void {
        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->syncCustomRole($role)
            ->create();
        $this->actingAs($user, 'sanctum');

        $product = Product::factory()
            ->createOne();
        $response = $this->request()
            ->deleteJson(
                route('api.v1.admin.products.force-delete', [
                    'product' => $product->id,
                ])
            );
        $response->assertStatus(403)
            ->assertJson(fn (AssertableJson $json) => $json->where(
                'message',
                'User does not have the right roles.')
                ->etc()
            );

    }

    #[Test]
    #[DataProvider('successfulProductFilters')]
    public function is_product_filters_work_successful(
        string $key,
        string $operator,
        mixed $value
    ): void {
        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->syncAdminRole()
            ->create();
        $this->actingAs($user, 'sanctum');

        $product = Product::factory()
            ->createMany(20);

        $response = $this->request()
            ->get(
                route('api.v1.admin.products.index'),
                [
                    'filters' => [
                        [
                            'key' => $key,
                            'operator' => $operator,
                            'value' => $value,
                        ],
                    ],
                ]
            );
        $response->assertStatus(200);

        $response->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn (AssertableJson $collection) => $collection->each(
                fn (AssertableJson $item) => $item->has('id')->etc()
            )
            )
            ->etc()
        );
    }

    #[Test]
    #[DataProvider('failedProductPermission')]
    public function it_not_created_product_successful_by_user_permission(
        mixed $role,
        string $message
    ): void {

        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->syncCustomRole($role)
            ->createOne();

        $this->actingAs($user, 'sanctum');

        $product = Product::factory()
            ->makeOne();
        $response = $this->request()
            ->postJson(
                route('api.v1.admin.products.store'),
                $product->toArray()
            );

        $response->assertStatus(403)
            ->assertJson(fn (AssertableJson $json) => $json->where('message', $message)
                ->etc()
            );
    }

    #[Test]
    #[DataProvider('failedCreateVerification')]
    public function it_not_created_product_successful_by_validation(
        array $columns,
        array $expectedErrors,
        string $message
    ): void {
        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->syncAdminRole()
            ->create();
        $this->actingAs($user, 'sanctum');

        $product = Product::factory()
            ->makeOne()
            ->toArray();
        foreach ($columns as $field => $value) {
            $product[$field] = $value;
        }
        $response = $this->request()
            ->postJson(
                route('api.v1.admin.products.store'),
                $product
            );
        $response->assertStatus(422)
            ->assertJsonValidationErrors($expectedErrors)
            ->assertJson(fn (AssertableJson $json) => $json->whereContains(
                'errors.title',
                $message
            )->etc()
            );

    }

    #[Test]
    #[DataProvider('failedUpdateVerification')]
    public function it_not_updated_product_successful_by_validation(
        array $columns,
        string $expectedErrors,
        string $message
    ): void {
        $update = [
            'title' => 'Valid Title',
            'SKU' => 'VALIDSKU123',
            'price' => 100,
            'quantity' => 10,
            'categories' => [Category::factory()->createOne()->getKey()],
        ];

        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->syncAdminRole()
            ->create();
        $this->actingAs($user, 'sanctum');

        $product = Product::factory()
            ->createOne();

        foreach ($columns as $field => $value) {
            $update[$field] = $value;
        }
        $response = $this->request()
            ->putJson(
                route('api.v1.admin.products.update', [
                    'product' => $product->id,
                ]),
                $update
            );

        $response->assertStatus(422)
            ->assertJsonValidationErrors([$expectedErrors])
            ->assertJson(fn (AssertableJson $json) => $json->whereContains(
                "errors.$expectedErrors",
                $message
            )->etc()
            );

    }

    public static function successfulProductFilters()
    {
        return [
            'filter by product has discount' => [
                'discount',
                '>',
                '1',
            ],
            'filter by product dont have discount' => [
                'discount',
                '=',
                '0',
            ],
            'filter by product count greater than' => [
                'quantity',
                '>',
                '5',
            ],
            'filter by product has description' => [
                'description',
                '!=',
                null,
            ],
            'filter by product dont have description' => [
                'product_count',
                '=',
                null,
            ],
        ];
    }

    public static function failedUpdateVerification(): array
    {
        return [
            'title.max' => [
                [
                    'title' => Str::random(256),
                ],
                'title',
                'The title field must not be greater than 255 characters.',
            ],
            'title.required' => [
                [
                    'title' => null,
                ],
                'title',
                'The title field is required.',
            ],
            'SKU.required' => [
                [
                    'SKU' => null,
                ],
                'SKU',
                'The s k u field is required.',
            ],
            'price.required' => [
                [
                    'price' => null,
                ],
                'price',
                'The price field is required.',
            ],
            'price.invalid' => [
                [
                    'price' => 'price',
                ],
                'price',
                'The price field must be a number.',
            ],
            'price.min' => [
                [
                    'price' => 0,
                ],
                'price',
                'The price field must be at least 1.',
            ],
            'discount.min' => [
                [
                    'discount' => 0,
                ],
                'discount',
                'The discount field must be at least 1.',
            ],
            'discount.max' => [
                [
                    'discount' => 100,
                ],
                'discount',
                'The discount field must not be greater than 99.',
            ],
            'quantity.invalid' => [
                [
                    'quantity' => 0,
                ],
                'quantity',
                'The quantity field must be at least 1.',
            ],
            'quantity.required' => [
                [
                    'quantity' => null,
                ],
                'quantity',
                'The quantity field is required.',
            ],
            'thumbnail.invalid' => [
                [
                    'thumbnail' => UploadedFile::fake()->createWithContent(
                        'test.csv',
                        "name,email\nJohn,john@example.com\nJane,jane@example.com"
                    ),
                ],
                'thumbnail',
                'The thumbnail field must be an image.',
            ],
            'categories.required' => [
                [
                    'categories' => [null],
                ],
                'categories.0',
                'The categories.0 field is required.',
            ],
            'categories.*.numeric' => [
                [
                    'categories' => ['ABC'],
                ],
                'categories.0',
                'The categories.0 field must be a number.',
            ],
            'categories.*.invalid' => [
                [
                    'categories' => [9999999],
                ],
                'categories.0',
                'The selected categories.0 is invalid.',
            ],
        ];
    }

    public static function failedCreateVerification(): array
    {
        return [
            'title.required' => [
                [
                    'title' => null,
                ],
                ['title'],
                'The title field is required.',
            ],
        ];
    }

    public static function failedProductPermission(): array
    {
        return [
            'permission.user' => [
                RolesEnum::USER,
                'User does not have the right roles.',
            ],
            'permission.seller' => [
                RolesEnum::SELLER,
                'User does not have the right roles.',
            ],
        ];
    }
}
