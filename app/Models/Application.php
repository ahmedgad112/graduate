<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const GRADES = ['excellent', 'very_good', 'good', 'pass'];

    public const EMPLOYMENT_EMPLOYED = 'employed';

    public const EMPLOYMENT_SEEKING = 'seeking';

    public const EMPLOYMENT_UNEMPLOYED = 'unemployed';

    /** @return list<string> */
    public static function employmentStatuses(): array
    {
        return [
            self::EMPLOYMENT_EMPLOYED,
            self::EMPLOYMENT_SEEKING,
            self::EMPLOYMENT_UNEMPLOYED,
        ];
    }

    protected $fillable = [
        'name',
        'email',
        'phone',
        'national_id',
        'address',
        'university_id',
        'department_id',
        'specialization_id',
        'graduation_year_id',
        'grade',
        'gpa',
        'cv_path',
        'cert_path',
        'photo_path',
        'skills',
        'certificates_text',
        'employment_status',
        'exempt_from_military',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'gpa' => 'decimal:2',
            'exempt_from_military' => 'boolean',
        ];
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function specialization(): BelongsTo
    {
        return $this->belongsTo(Specialization::class);
    }

    public function graduationYear(): BelongsTo
    {
        return $this->belongsTo(GraduationYear::class);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * @return array<string, string>
     */
    public static function gradeLabels(): array
    {
        return [
            'excellent' => 'ممتاز',
            'very_good' => 'جيد جداً',
            'good' => 'جيد',
            'pass' => 'مقبول',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function employmentStatusLabels(): array
    {
        return [
            self::EMPLOYMENT_EMPLOYED => 'يعمل',
            self::EMPLOYMENT_SEEKING => 'أبحث عن عمل',
            self::EMPLOYMENT_UNEMPLOYED => 'لا أعمل',
        ];
    }

    public static function militaryExemptionLabel(?bool $exempt): string
    {
        if ($exempt === null) {
            return '—';
        }

        return $exempt ? 'نعم (معفى / موقوف)' : 'لا';
    }

    /**
     * @return array<string, string>
     */
    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDING => 'قيد المراجعة',
            self::STATUS_APPROVED => 'موافق عليه',
            self::STATUS_REJECTED => 'مرفوض',
        ];
    }
}
