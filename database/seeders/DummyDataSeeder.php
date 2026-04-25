<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Department;
use App\Models\GraduationYear;
use App\Models\Profile;
use App\Models\University;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        if (Application::query()->exists()) {
            $this->command?->warn('Dummy data skipped: applications table is not empty.');

            return;
        }

        if (! University::query()->exists()) {
            $this->command?->error('Dummy data requires base seed (universities). Run DatabaseSeeder first.');

            return;
        }

        $universities = $this->ensureUniversities();
        $department = Department::query()->active()->whereHas('specializations')->with('specializations')->first();
        if (! $department) {
            $this->command?->error('No active department with specializations found.');

            return;
        }
        $specs = $department->specializations()->active()->get();
        if ($specs->isEmpty()) {
            $this->command?->error('No specializations in department.');

            return;
        }
        $graduationYearIds = GraduationYear::query()->active()->pluck('id', 'year')->all();
        if ($graduationYearIds === []) {
            $this->command?->error('No active graduation years found.');

            return;
        }

        $governorates = array_keys(Application::GOVERNORATES);
        $regions = ['مدينة نصر', 'الزقازيق', '6 أكتوبر', 'بورسعيد', 'الاسكندرية', 'طنطا'];

        $specsList = $specs->all();

        DB::transaction(function () use ($universities, $department, $specsList, $graduationYearIds, $governorates, $regions): void {
            for ($i = 1; $i <= 12; $i++) {
                $spec = $specsList[($i - 1) % count($specsList)];
                $u = $universities[($i - 1) % count($universities)];
                $yid = $graduationYearIds[array_rand($graduationYearIds)];

                Application::factory()->create([
                    'name' => 'طالب بانتظار '.$i,
                    'email' => "pending-{$i}@dummy-graduate.test",
                    'phone' => sprintf('05%09d', 300000 + $i),
                    'national_id' => sprintf('290201%08d', 1000 + $i),
                    'status' => Application::STATUS_PENDING,
                    'university_id' => $u->id,
                    'department_id' => $department->id,
                    'specialization_id' => $spec->id,
                    'graduation_year_id' => $yid,
                    'governorate' => $governorates[($i - 1) % count($governorates)],
                    'residence_region' => $regions[($i - 1) % count($regions)],
                ]);
            }

            for ($i = 1; $i <= 5; $i++) {
                $spec = $specsList[($i - 1) % count($specsList)];
                $u = $universities[($i) % count($universities)];
                $yid = $graduationYearIds[array_rand($graduationYearIds)];

                Application::factory()->create([
                    'name' => 'طلب مرفوض '.$i,
                    'email' => "rejected-{$i}@dummy-graduate.test",
                    'phone' => sprintf('05%09d', 400000 + $i),
                    'national_id' => sprintf('290202%08d', 1000 + $i),
                    'status' => Application::STATUS_REJECTED,
                    'university_id' => $u->id,
                    'department_id' => $department->id,
                    'specialization_id' => $spec->id,
                    'graduation_year_id' => $yid,
                    'governorate' => $governorates[($i + 3) % count($governorates)],
                    'residence_region' => $regions[($i + 2) % count($regions)],
                ]);
            }

            for ($i = 1; $i <= 6; $i++) {
                $spec = $specsList[$i % count($specsList)];
                $uni = $universities[($i + 1) % count($universities)];
                $yid = $graduationYearIds[array_rand($graduationYearIds)];
                $email = "graduate-{$i}@dummy-graduate.test";
                $phone = sprintf('01%09d', 5000000 + $i);
                $nationalId = sprintf('290203%08d', 1000 + $i);
                $gov = $governorates[($i + 5) % count($governorates)];
                $addr = 'العنوان نموذجي — حي '.($i);

                $user = User::query()->create([
                    'name' => 'خريج معتمد '.$i,
                    'email' => $email,
                    'phone' => $phone,
                    'password' => 'password',
                    'role' => User::ROLE_STUDENT,
                ]);
                $user->assignRole(User::ROLE_STUDENT);

                Application::factory()->create([
                    'name' => $user->name,
                    'email' => $email,
                    'phone' => $phone,
                    'national_id' => $nationalId,
                    'status' => Application::STATUS_APPROVED,
                    'university_id' => $uni->id,
                    'department_id' => $department->id,
                    'specialization_id' => $spec->id,
                    'graduation_year_id' => $yid,
                    'address' => $addr,
                    'governorate' => $gov,
                    'residence_region' => $regions[($i + 1) % count($regions)],
                ]);

                Profile::query()->create([
                    'user_id' => $user->id,
                    'national_id' => $nationalId,
                    'governorate' => $gov,
                    'residence_region' => $regions[($i + 1) % count($regions)],
                    'address' => $addr,
                    'university_name' => $uni->name,
                    'department_id' => $department->id,
                    'specialization_id' => $spec->id,
                    'graduation_year_id' => $yid,
                    'grade' => Application::GRADES[($i - 1) % count(Application::GRADES)],
                    'gpa' => 2.0 + ($i * 0.1),
                    'cv_path' => null,
                    'cert_path' => null,
                    'photo_path' => null,
                    'skills' => 'PHP, Laravel, SQL',
                    'certificates_text' => null,
                    'employment_status' => Application::EMPLOYMENT_EMPLOYED,
                    'exempt_from_military' => false,
                ]);
            }
        });

        $this->command?->info('Dummy data: 12 pending, 5 rejected, 6 approved (with users and profiles).');
        $this->command?->info('Dummy students login: graduate-1@dummy-graduate.test … graduate-6@dummy-graduate.test — password: password');
    }

    /**
     * @return list<University>
     */
    private function ensureUniversities(): array
    {
        $names = [
            'جامعة نموذجية',
            'جامعة القاهرة',
            'جامعة عين شمس',
        ];

        $list = [];
        foreach ($names as $name) {
            $u = University::query()->firstOrCreate(
                ['name' => $name],
                ['is_active' => true]
            );
            if (! $u->is_active) {
                $u->update(['is_active' => true]);
            }
            $list[] = $u;
        }

        return $list;
    }
}
