<?php

namespace App\Models;

use App\Models\Concerns\HasArabicActivityDescriptions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class GraduationYear extends Model
{
    use HasArabicActivityDescriptions, LogsActivity;

    protected $fillable = [
        'year',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('graduation_years')
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(self::arabicActivityDescription());
    }
}
