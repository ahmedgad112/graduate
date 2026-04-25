<?php

namespace App\Models;

use App\Models\Concerns\HasArabicActivityDescriptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Profile extends Model
{
    use HasArabicActivityDescriptions, LogsActivity;

    protected $fillable = [
        'user_id',
        'national_id',
        'governorate',
        'residence_region',
        'address',
        'university_name',
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
    ];

    protected function casts(): array
    {
        return [
            'gpa' => 'decimal:2',
            'exempt_from_military' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('profiles')
            ->logFillable()
            ->logExcept(['cv_path', 'cert_path', 'photo_path', 'national_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(self::arabicActivityDescription());
    }
}
