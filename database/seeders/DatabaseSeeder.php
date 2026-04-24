<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\GraduationYear;
use App\Models\University;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolePermissionSeeder::class);

        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@graduate.local'],
            [
                'name' => 'مدير النظام',
                'phone' => null,
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
            ]
        );
        $admin->syncRoles([User::ROLE_ADMIN]);

        $reviewer = User::query()->firstOrCreate(
            ['email' => 'reviewer@graduate.local'],
            [
                'name' => 'مراجع الطلبات',
                'phone' => null,
                'password' => Hash::make('password'),
                'role' => User::ROLE_REVIEWER,
            ]
        );
        $reviewer->syncRoles([User::ROLE_REVIEWER]);

        University::query()->firstOrCreate(
            ['name' => 'جامعة نموذجية'],
            ['is_active' => true]
        );

        foreach ([2024, 2025, 2026] as $year) {
            GraduationYear::query()->firstOrCreate(
                ['year' => $year],
                ['is_active' => true]
            );
        }

        $department = Department::query()->firstOrCreate(
            ['name' => 'كلية الهندسة'],
            ['is_active' => true]
        );

        $department->specializations()->firstOrCreate(
            ['name' => 'هندسة البرمجيات'],
            ['is_active' => true]
        );

        $department->specializations()->firstOrCreate(
            ['name' => 'هندسة الشبكات'],
            ['is_active' => true]
        );

        $roleNames = Role::query()->where('guard_name', 'web')->pluck('name')->all();
        User::query()->orderBy('id')->chunkById(100, function ($users) use ($roleNames): void {
            $users->each(function (User $user) use ($roleNames): void {
                if (in_array($user->role, $roleNames, true)) {
                    $user->syncRoles([$user->role]);
                }
            });
        });
    }
}
