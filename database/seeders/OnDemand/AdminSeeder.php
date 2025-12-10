<?php

namespace Database\Seeders\OnDemand;

use App\Enums\Permissions\AdminEnum;
use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Testing\Fluent\Concerns\Has;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::createOrFirst([
            'email' => config('services.admin.email'),
        ], [
            'email' => config('services.admin.email'),
            'name' => 'Admin',
            'surname' => 'Main',
            'phone' => "+380000000000",
            'password' => \Hash::make(config('services.admin.password'))
        ]);
        if(! $user->hasRole(RolesEnum::ADMIN)){
            $user->assignRole(RolesEnum::ADMIN);
        }
    }
}
