<?php

namespace App\Exports;

use App\Models\Application;
use App\Models\Profile;
use App\Models\University;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GraduatesExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private array $filters) {}

    public function collection(): Collection
    {
        $query = Profile::query()->with(['user', 'department', 'specialization', 'graduationYear']);

        if (! empty($this->filters['graduation_year_id'])) {
            $query->where('graduation_year_id', $this->filters['graduation_year_id']);
        }

        if (! empty($this->filters['grade'])) {
            $query->where('grade', $this->filters['grade']);
        }

        if (! empty($this->filters['university_id'])) {
            $university = University::query()->find($this->filters['university_id']);
            if ($university) {
                $query->where('university_name', $university->name);
            }
        }

        if (! empty($this->filters['department_id'])) {
            $query->where('department_id', $this->filters['department_id']);
        }

        return $query->orderByDesc('profiles.id')->get();
    }

    public function headings(): array
    {
        return [
            'الاسم',
            'البريد الإلكتروني',
            'الهاتف',
            'الرقم الوطني',
            'الجامعة',
            'القسم',
            'التخصص',
            'سنة التخرج',
            'التقدير',
            'المعدل',
            'المهارات',
            'شهادات ودورات (نص)',
            'حالة العمل',
            'موقوف من التجنيد',
        ];
    }

    /**
     * @param  Profile  $profile
     */
    public function map($profile): array
    {
        $user = $profile->user;

        return [
            $user?->name,
            $user?->email,
            $user?->phone,
            $profile->national_id,
            $profile->university_name,
            $profile->department?->name,
            $profile->specialization?->name,
            $profile->graduationYear?->year,
            Application::gradeLabels()[$profile->grade] ?? $profile->grade,
            $profile->gpa,
            $profile->skills,
            $profile->certificates_text,
            Application::employmentStatusLabels()[$profile->employment_status] ?? $profile->employment_status,
            Application::militaryExemptionLabel($profile->exempt_from_military),
        ];
    }
}
