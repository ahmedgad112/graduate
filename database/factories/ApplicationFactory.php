<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Department;
use App\Models\GraduationYear;
use App\Models\University;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Application>
 */
class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    private static array $arabicNames = [
        'أحمد محمد علي', 'فاطمة حسن إبراهيم', 'محمود سعيد محمود', 'مريم خالد عبدالله',
        'يوسف عادل فهمي', 'نور الدين رضا', 'سلمى كمال الدين', 'عمر وائل مصطفى',
        'شيماء ناصر', 'كريم حاتم', 'ليلى ماهر', 'تامر سمير', 'رنا وجدي', 'وائل عبدالرحمن',
    ];

    private static array $regions = [
        'مدينة نصر', 'الزقازيق', 'وسط البلد', '6 أكتوبر', 'السيدة زينب', 'المنصورة', 'دمنهور', 'سوهاج', 'قنا', 'بورسعيد',
    ];

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $university = University::query()->active()->inRandomOrder()->first();
        if (! $university) {
            throw new \RuntimeException('Seeding requires at least one active university. Run DatabaseSeeder first.');
        }
        $department = Department::query()->active()->whereHas('specializations')->inRandomOrder()->first();
        if (! $department) {
            throw new \RuntimeException('Seeding requires at least one active department with specializations. Run DatabaseSeeder first.');
        }
        $spec = $department->specializations()->active()->inRandomOrder()->first();
        if (! $spec) {
            throw new \RuntimeException('Seeding requires specializations. Run DatabaseSeeder first.');
        }
        $graduationYear = GraduationYear::query()->active()->inRandomOrder()->first();
        if (! $graduationYear) {
            throw new \RuntimeException('Seeding requires graduation years. Run DatabaseSeeder first.');
        }
        $govKeys = array_keys(Application::GOVERNORATES);

        return [
            'name' => fake()->randomElement(self::$arabicNames).' '.fake()->numerify('##'),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->numerify('05#########'),
            'national_id' => fake()->unique()->numerify('2#############'),
            'address' => 'شارع '.fake()->randomElement(self::$regions).' — '.fake()->bothify('??###'),
            'governorate' => fake()->randomElement($govKeys),
            'residence_region' => fake()->randomElement(self::$regions),
            'university_id' => $university->id,
            'department_id' => $department->id,
            'specialization_id' => $spec->id,
            'graduation_year_id' => $graduationYear->id,
            'grade' => fake()->randomElement(Application::GRADES),
            'gpa' => fake()->randomFloat(2, 2.0, 3.75),
            'cv_path' => null,
            'cert_path' => null,
            'photo_path' => null,
            'skills' => 'Laravel, PHP, MySQL, Git — '.fake()->sentence(),
            'certificates_text' => 'شهادات: '.fake()->randomElement(['ICDL', 'CCNA', 'Python', 'Data Analysis']),
            'employment_status' => fake()->randomElement(Application::employmentStatuses()),
            'exempt_from_military' => fake()->boolean(20),
            'status' => Application::STATUS_PENDING,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => [
            'status' => Application::STATUS_PENDING,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn () => [
            'status' => Application::STATUS_REJECTED,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn () => [
            'status' => Application::STATUS_APPROVED,
        ]);
    }
}
