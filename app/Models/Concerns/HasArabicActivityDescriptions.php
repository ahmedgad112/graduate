<?php

namespace App\Models\Concerns;

use Closure;

trait HasArabicActivityDescriptions
{
    /**
     * @return Closure(string): string
     */
    protected static function arabicActivityDescription(): Closure
    {
        return function (string $eventName) {
            return match ($eventName) {
                'created' => 'تم الإنشاء',
                'updated' => 'تم التعديل',
                'deleted' => 'تم الحذف',
                'restored' => 'تم الاسترجاع',
                default => $eventName,
            };
        };
    }
}
