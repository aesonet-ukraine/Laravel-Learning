<?php

namespace Database\Factories;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'surname' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->e164PhoneNumber(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
                'remember_token' => null,
            ];
        });
    }

    public function fillPhone(string $phone = '380671111111'): self
    {
        return $this->state(function (array $attributes) use ($phone) {
            return [
                'phone' => $phone,
            ];
        });
    }

    public function syncAdminRole(string $password): self
    {
        return $this->afterCreating(function (User $user) {
            $user->syncRoles(RolesEnum::ADMIN);
        });
    }
}
