<?php

namespace Database\Seeders;

use Database\Seeders\OnDemand\AdminSeeder;
use Database\Seeders\OnDemand\PermissionsAndRolesSeeder;
use Illuminate\Database\Seeder;

class OnDemandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(PermissionsAndRolesSeeder::class);
        $this->call(AdminSeeder::class);
    }
}
