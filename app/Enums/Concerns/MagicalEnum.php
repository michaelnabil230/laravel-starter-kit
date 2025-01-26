<?php

declare(strict_types=1);

namespace App\Enums\Concerns;

use ReflectionEnum;

trait MagicalEnum
{
    /**
     * Retrieves an array of names representing the cases of the enumeration.
     *
     * @return array<string|int, string|int>
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * Retrieves an array of values representing the cases of the enumeration.
     *
     * @return array<string|int, string|int>
     */
    public static function values(): array
    {
        return self::isBackedEnum()
            ? array_column(self::cases(), 'value')
            : [];
    }

    /**
     * Checks if the enumeration type is backed by a specific backing type.
     */
    public static function isBackedEnum(): bool
    {
        return (new ReflectionEnum(self::class))->isBacked();
    }

    /**
     * Converts the enumeration type to an associative array.
     *
     * @return array<string|int, string|int>
     */
    public static function toArray(): array
    {
        if (self::isBackedEnum() === false) {
            return self::names();
        }

        return array_column(self::cases(), 'value', 'name');
    }

    /**
     * Generates an associative array mapping case values to their corresponding names in the enumeration type.
     *
     * @return array<string|int, string|int>
     */
    public static function toReverseArray(): array
    {
        if (self::isBackedEnum() === false) {
            return self::names();
        }

        return array_column(self::cases(), 'name', 'value');
    }
}
