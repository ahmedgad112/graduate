<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Concerns\HasArabicActivityDescriptions;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'phone', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use CausesActivity, HasArabicActivityDescriptions, HasFactory, HasRoles, LogsActivity, Notifiable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_REVIEWER = 'reviewer';

    public const ROLE_STUDENT = 'student';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }

    public function isStudent(): bool
    {
        return $this->hasRole(self::ROLE_STUDENT);
    }

    public function canAccessAdminPanel(): bool
    {
        return $this->getAllPermissions()->isNotEmpty();
    }

    /**
     * @return list<string>
     */
    public static function assignableRoles(): array
    {
        return [self::ROLE_ADMIN, self::ROLE_REVIEWER, self::ROLE_STUDENT];
    }

    public static function roleLabel(string $role): string
    {
        return match ($role) {
            self::ROLE_ADMIN => 'مدير النظام',
            self::ROLE_REVIEWER => 'مراجع الطلبات',
            self::ROLE_STUDENT => 'خريج',
            default => $role,
        };
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('users')
            ->logOnly(['name', 'email', 'phone', 'role'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(self::arabicActivityDescription());
    }
}
