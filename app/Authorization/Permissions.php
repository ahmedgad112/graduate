<?php

namespace App\Authorization;

final class Permissions
{
    public const DASHBOARD_VIEW = 'dashboard.view';

    public const APPLICATIONS_MANAGE = 'applications.manage';

    public const CATALOG_MANAGE = 'catalog.manage';

    public const GRADUATES_EXPORT = 'graduates.export';

    public const USERS_MANAGE = 'users.manage';

    public const ROLES_MANAGE = 'roles.manage';

    public const ACTIVITY_LOG_VIEW = 'activity_log.view';

    /**
     * @return list<string>
     */
    public static function all(): array
    {
        return [
            self::DASHBOARD_VIEW,
            self::APPLICATIONS_MANAGE,
            self::CATALOG_MANAGE,
            self::GRADUATES_EXPORT,
            self::USERS_MANAGE,
            self::ROLES_MANAGE,
            self::ACTIVITY_LOG_VIEW,
        ];
    }

    public static function label(string $permission): string
    {
        return match ($permission) {
            self::DASHBOARD_VIEW => 'لوحة التحكم والطلاب حسب سنة التخرج',
            self::APPLICATIONS_MANAGE => 'طلبات التسجيل (مراجعة وموافقة)',
            self::CATALOG_MANAGE => 'الجامعات والأقسام وسنوات التخرج',
            self::GRADUATES_EXPORT => 'تصدير بيانات الخريجين (Excel)',
            self::USERS_MANAGE => 'إدارة المستخدمين',
            self::ROLES_MANAGE => 'إدارة الأدوار والصلاحيات',
            self::ACTIVITY_LOG_VIEW => 'سجل النشاط (المراجعة والتتبع)',
            default => $permission,
        };
    }
}
