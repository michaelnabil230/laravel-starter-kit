<?php

declare(strict_types=1);

namespace App\Enums\Concerns;

use BackedEnum;

trait MagicalEnum
{
    /**
     * Get all enum case names.
     *
     * @return list<string>
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * Get all enum case values.
     *
     * @return list<int>|list<string>
     */
    public static function values(): array
    {
        return self::isBackedEnum()
            ? array_column(self::cases(), 'value')
            : [];
    }

    /**
     * Determine whether the enum is a backed enum.
     */
    public static function isBackedEnum(): bool
    {
        return collect(class_implements(self::class))
            ->contains(BackedEnum::class);
    }

    /**
     * Convert enum cases to an array.
     *
     * @return list<string>|array<string, int|string>
     */
    public static function toArray(): array
    {
        return self::isBackedEnum()
            ? array_column(self::cases(), 'value', 'name')
            : self::names();
    }

    /**
     * Convert enum cases to a reverse array.
     *
     * @return list<string>|array<int|string, string>
     */
    public static function toReverseArray(): array
    {
        return self::isBackedEnum()
            ? array_column(self::cases(), 'name', 'value')
            : self::names();
    }
}
