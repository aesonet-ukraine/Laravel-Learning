<?php

namespace Http\Controllers\Api;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\FeatureTestCase;

class AuthControllerTest extends FeatureTestCase
{
    /**
     * A basic feature test example.
     */
    #[Test]
    public function is_created_user_with_valid_data(): void
    {
        $password = 'password1234';
        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->makeOne();

        $response = $this->request()
            ->postJson(route('api.auth.register'), [
                ...$user->toArray(),
                'password' => $password,
                'password_confirmation' => $password,
            ]);
        $response->assertStatus(201);

        $response
            ->assertJson(fn (AssertableJson $json) => $json->where(
                'data.email',
                fn (string $email) => str($email)->is($user->email)
            )
                ->whereContains('data.roles', RolesEnum::USER->value)
                ->missing('data.password')
                ->etc());
    }

    #[Test]
    #[DataProvider('failedVerification')]
    public function is_not_created_user_with_invalid_data(
        string $column,
        mixed $value,
        string $message
    ): void {
        $password = 'password1234';
        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->makeOne([
                $column => $value,
            ]);

        $response = $this->request()
            ->postJson(route('api.auth.register'), [
                ...$user->toArray(),
                'password' => $password,
                'password_confirmation' => $password,
            ]);
        //        $response->assertStatus(422);

        $response->assertJsonValidationErrors([$column]);

        $response->assertJson(
            fn (AssertableJson $json) => $json->whereContains("errors.$column", $message)->etc()
        );
    }

    #[Test]
    #[DataProvider('uniqueVerification')]
    public function it_returns_an_error_on_duplicated(string $column, string $value, string $message): void
    {
        User::factory()->createOne([
            $column => $value,
        ]);

        $user = User::factory()
            ->unverified()
            ->fillPhone()
            ->makeOne([
                $column => $value,
            ]);

        $response = $this->request()
            ->postJson(route('api.auth.register'), [
                ...$user->toArray(),
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

        $response->assertJsonValidationErrors([$column]);
        $response->assertJson(
            fn (AssertableJson $json) => $json->whereContains("errors.$column", $message)->etc()
        );
    }

    public static function failedVerification(): array
    {
        return [
            'name.required' => [
                'name',
                '',
                'Name is required',
            ],
            'name.min' => [
                'name',
                'A',
                'Name must be at least 3 characters',
            ],
            'name.max' => [
                'name',
                Str::random(31),
                'Name must not exceed 30 characters',
            ],
            'surname.required' => [
                'surname',
                '',
                'Surname is required',
            ],
            'surname.min' => [
                'surname',
                'B',
                'Surname must be at least 3 characters',
            ],
            'surname.max' => [
                'surname',
                Str::random(51),
                'Surname must not exceed 50 characters',
            ],
            'phone.required' => [
                'phone',
                '',
                'Phone is required',
            ],
            'phone.max' => [
                'phone',
                Str::random(16),
                'Phone must not exceed 15 characters',
            ],
            'email.required' => [
                'email',
                '',
                'Email is required',
            ],
            'email.email' => [
                'email',
                'invalid-email',
                'The email must be a valid email address.',
            ],
        ];
    }

    public static function uniqueVerification(): array
    {

        return [
            'email' => [
                'email',
                'user@test.com',
                'Email is already exist',
            ],
            'phone' => [
                'phone',
                '380930000011',
                'Phone is already exist',
            ],
        ];

    }
}
