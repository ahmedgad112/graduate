<?php

namespace App\Models;

use App\Models\Concerns\HasArabicActivityDescriptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Application extends Model
{
    use HasArabicActivityDescriptions, LogsActivity;

    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const GRADES = ['excellent', 'very_good', 'good', 'pass'];

    public const EMPLOYMENT_EMPLOYED = 'employed';

    public const EMPLOYMENT_SEEKING = 'seeking';

    public const EMPLOYMENT_UNEMPLOYED = 'unemployed';

    /**
     * محافظات مصر (مفتاح إنجليزي مختصر للتخزين، التسمية بالعربية للعرض).
     *
     * @var array<string, string>
     */
    public const GOVERNORATES = [
        'cairo' => 'القاهرة',
        'giza' => 'الجيزة',
        'alexandria' => 'الإسكندرية',
        'qalyubia' => 'القليوبية',
        'beheira' => 'البحيرة',
        'matrouh' => 'مطروح',
        'damietta' => 'دمياط',
        'dakahlia' => 'الدقهلية',
        'sharqia' => 'الشرقية',
        'kafr_el_sheikh' => 'كفر الشيخ',
        'gharbia' => 'الغربية',
        'monufia' => 'المنوفية',
        'port_said' => 'بورسعيد',
        'ismailia' => 'الإسماعيلية',
        'suez' => 'السويس',
        'north_sinai' => 'شمال سيناء',
        'south_sinai' => 'جنوب سيناء',
        'red_sea' => 'البحر الأحمر',
        'fayoum' => 'الفيوم',
        'beni_suef' => 'بني سويف',
        'minya' => 'المنيا',
        'asyut' => 'أسيوط',
        'sohag' => 'سوهاج',
        'qena' => 'قنا',
        'luxor' => 'الأقصر',
        'aswan' => 'أسوان',
        'new_valley' => 'الوادي الجديد',
    ];

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
        'governorate',
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
    public static function governorateLabels(): array
    {
        return self::GOVERNORATES;
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('applications')
            ->logFillable()
            ->logExcept(['cv_path', 'cert_path', 'photo_path', 'national_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(self::arabicActivityDescription());
    }
}
