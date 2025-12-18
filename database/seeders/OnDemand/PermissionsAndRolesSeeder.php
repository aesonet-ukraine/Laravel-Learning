<?php

namespace Database\Seeders\OnDemand;

use App\Enums\Permissions\AccountEnum;
use App\Enums\Permissions\CategoryEnum;
use App\Enums\Permissions\OrderEnum;
use App\Enums\Permissions\ProductEnum;
use App\Enums\Permissions\UserEnum;
use App\Enums\RolesEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsAndRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ...AccountEnum::values(),
            ...OrderEnum::values(),
            ...ProductEnum::values(),
            ...CategoryEnum::values(),
            ...UserEnum::values()
        ];
        $guard = config('auth.defaults.guard', 'web');
        foreach ($permissions as $permission) {
          Permission::findOrCreate($permission, $guard);
        }
        $this->createRoleWithPermission(RolesEnum::USER, AccountEnum::values());
        $this->deleteRoleWithPermission(RolesEnum::USER, AccountEnum::values());


        $this->createRoleWithPermission(RolesEnum::MODERATOR, [
            ...OrderEnum::values(),
            ...ProductEnum::values(),
            ...CategoryEnum::values(),
            ...UserEnum::values()
        ]);
        $this->createRoleWithPermission(RolesEnum::SELLER, [
            ...OrderEnum::values(),
        ]);
        $this->createRoleWithPermission(RolesEnum::ADMIN);
    }
    protected function createRoleWithPermission(RolesEnum $role, ?array $permissions = null): void
    {
        if(! Role::where("name", $role)->exists()) {
            Role::create(["name" => $role])
                ->givePermissionTo($permissions ?? Permission::all());
        }
    }
    protected function deleteRoleWithPermission(RolesEnum $role, ?array $permissions = null): void
    {
        if(Role::where("name", $role)->exists()) {
            Role::findByName($role->value)
                ->revokePermissionTo($permissions ?? Permission::all());
        }
    }

}
