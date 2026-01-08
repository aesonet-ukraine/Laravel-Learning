<?php

namespace Http\Controllers\Api;

use App\Enums\RolesEnum;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\FeatureTestCase;

class CategoryControllerTest extends FeatureTestCase
{
    /**
     * A basic feature test example.
     */
    #[Test]
    public function it_created_category_successful(): void
    {

        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->syncAdminRole()
            ->create();
        $this->actingAs($user, 'sanctum');

        $category = Category::factory()
            ->makeOne();
        $response = $this->request()
            ->postJson(
                route('api.v1.admin.categories.store'),
                $category->toArray()
            );
        $response->assertStatus(201);
        $response
            ->assertJson(fn (AssertableJson $json) => $json->has('data.id')
                ->where('data.title', $category->title)
                ->where('data.slug', $category->slug)
                ->where('data.thumbnail', $category->thumbnail)
                ->whereNull('data.deleted_at')
                ->etc()
            );
    }

    #[Test]
    public function is_updated_category_successful_by_validation(): void
    {
        $title = 'Updated Category Title';
        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->syncAdminRole()
            ->create();
        $this->actingAs($user, 'sanctum');

        $category = Category::factory()
            ->createOne();
        $response = $this->request()
            ->putJson(
                route('api.v1.admin.categories.update', [
                    'category' => $category->id,
                ]), [
                    ...$category->toArray(),
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
    public function it_deleted_category_successful(): void
    {
        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->syncAdminRole()
            ->create();
        $this->actingAs($user, 'sanctum');

        $category = Category::factory()
            ->createOne();
        $response = $this->request()
            ->deleteJson(
                route('api.v1.admin.categories.delete', [
                    'category' => $category->id,
                ])
            );
        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json->has('data.id')
                ->where('data.id', $category->id)
                ->etc()
            );
    }

    #[Test]
    #[DataProvider('failedCategoryPermission')]
    public function it_not_deleted_category_successful(
        RolesEnum $role,
    ): void {
        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->syncCustomRole($role)
            ->create();
        $this->actingAs($user, 'sanctum');

        $category = Category::factory()
            ->createOne();
        $response = $this->request()
            ->deleteJson(
                route('api.v1.admin.categories.delete', [
                    'category' => $category->id,
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
    public function it_restore_deleted_category_successful(): void
    {
        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->syncAdminRole()
            ->create();
        $this->actingAs($user, 'sanctum');

        $category = Category::factory()
            ->createOne();
        $this->request()
            ->deleteJson(
                route('api.v1.admin.categories.delete', [
                    'category' => $category->id,
                ])
            );
        $response = $this->request()
            ->putJson(
                route('api.v1.admin.categories.restore', [
                    'category' => $category->id,
                ])
            );
        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json->has('data.id')
                ->where('data.id', $category->id)
                ->etc()
            );
    }

    #[Test]
    #[DataProvider('failedCategoryPermission')]
    public function it_not_restore_deleted_category_successful(
        RolesEnum $role,
    ): void {
        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->syncCustomRole($role)
            ->create();
        $this->actingAs($user, 'sanctum');

        $category = Category::factory()
            ->createOne();

        $this->request()
            ->deleteJson(
                route('api.v1.admin.categories.delete', [
                    'category' => $category->id,
                ])
            );
        $response = $this->request()
            ->putJson(
                route('api.v1.admin.categories.restore', [
                    'category' => $category->id,
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
    public function it_force_deleted_category_successful(): void
    {
        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->syncAdminRole()
            ->create();
        $this->actingAs($user, 'sanctum');
        $category = Category::factory()
            ->createOne();
        $response = $this->request()
            ->deleteJson(
                route('api.v1.admin.categories.force-delete', [
                    'category' => $category->id,
                ])
            );

        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json->has('data.id')
                ->where('data.id', $category->id)
                ->etc()
            );
    }

    #[Test]
    #[DataProvider('failedCategoryPermission')]
    public function it_force_not_deleted_category_successful(
        RolesEnum $role,
    ): void {
        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->syncCustomRole($role)
            ->create();
        $this->actingAs($user, 'sanctum');

        $category = Category::factory()
            ->createOne();
        $response = $this->request()
            ->deleteJson(
                route('api.v1.admin.categories.force-delete', [
                    'category' => $category->id,
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
    #[DataProvider('successfulCategoryFilters')]
    public function is_category_filters_work_successful(
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

        $category = Category::factory()
            ->createMany(20);

        $response = $this->request()
            ->get(
                route('api.v1.admin.categories.index'),
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
            ->has('data', fn (AssertableJson $collection) => $collection->each(fn (AssertableJson $item) => $item->has('id')->etc()
            )
            )
            ->etc()
        );
    }

    #[Test]
    #[DataProvider('failedCategoryPermission')]
    public function it_not_created_category_successful_by_user_permission(
        mixed $role,
        string $message
    ): void {

        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->syncCustomRole($role)
            ->createOne();

        $this->actingAs($user, 'sanctum');

        $category = Category::factory()
            ->makeOne();
        $response = $this->request()
            ->postJson(
                route('api.v1.admin.categories.store'),
                $category->toArray()
            );

        $response->assertStatus(403)
            ->assertJson(fn (AssertableJson $json) => $json->where('message', $message)
                ->etc()
            );
    }

    #[Test]
    #[DataProvider('failedCreateVerification')]
    public function it_not_created_category_successful_by_validation(
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

        $category = Category::factory()
            ->makeOne()
            ->toArray();
        foreach ($columns as $field => $value) {
            $category[$field] = $value;
        }
        $response = $this->request()
            ->postJson(
                route('api.v1.admin.categories.store'),
                $category
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
    public function it_not_updated_category_successful_by_validation(
        array $columns,
        string $expectedErrors,
        string $message
    ): void {
        $update = [];
        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->syncAdminRole()
            ->create();
        $this->actingAs($user, 'sanctum');

        $category = Category::factory()
            ->createOne();
        foreach ($columns as $field => $value) {
            $update[$field] = $value;
        }
        $response = $this->request()
            ->putJson(
                route('api.v1.admin.categories.update', [
                    'category' => $category->id,
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

    public static function successfulCategoryFilters()
    {
        return [
            'filter by parent exists' => [
                'parent',
                '=',
                '1',
            ],
            'filter by parent not exists' => [
                'parent',
                '=',
                '0',
            ],
            'filter by children exists' => [
                'children',
                '=',
                '1',
            ],
            'filter by children not exists' => [
                'children',
                '=',
                '0',
            ],
            'filter by product count greater than' => [
                'product_count',
                '>',
                '5',
            ],
            'filter by product count less than' => [
                'product_count',
                '<',
                '10',
            ],
        ];
    }

    public static function failedUpdateVerification(): array
    {
        return [
            'title.min' => [
                [
                    'title' => 'a',
                ],
                'title',
                'The title field must be at least 2 characters.',
            ],
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
                    'parent_id' => null,
                ],
                'title',
                'The title field is required when parent id is not present.',
            ],
            'parent.required' => [
                [
                    'title' => null,
                    'parent_id' => null,
                ],
                'parent_id',
                'The parent id field is required when title is not present.',
            ],
            'parent.null' => [
                [
                    'title' => 'Valid Title',
                    'parent_id' => null,
                ],
                'parent_id',
                'The parent id field must be a number.',
            ],
            'parent.invalid' => [
                [
                    'title' => 'Valid Title',
                    'parent_id' => 0,
                ],
                'parent_id',
                'The selected parent id is invalid.',
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

    public static function failedCategoryPermission(): array
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
