<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\GraduationYear;
use App\Models\University;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => 'admin@graduate.local'],
            [
                'name' => 'مدير النظام',
                'phone' => null,
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
            ]
        );

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
    }
}
