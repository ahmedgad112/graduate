<?php

namespace Database\Seeders;

use App\Authorization\Permissions;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (Permissions::all() as $name) {
            Permission::findOrCreate($name);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $admin = Role::findOrCreate(User::ROLE_ADMIN);
        $admin->syncPermissions(
            Permission::query()->where('guard_name', 'web')->pluck('name')->all()
        );

        $reviewer = Role::findOrCreate(User::ROLE_REVIEWER);
        $reviewer->syncPermissions([
            Permissions::DASHBOARD_VIEW,
            Permissions::APPLICATIONS_MANAGE,
        ]);

        $student = Role::findOrCreate(User::ROLE_STUDENT);
        $student->syncPermissions();
    }
}
