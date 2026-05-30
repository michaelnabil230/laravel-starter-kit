<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Validation\Rule as BaseRule;
use Illuminate\Validation\Rules\Date;

final class Rule extends BaseRule
{
    /**
     * Get validation rules for birth date.
     */
    public static function birthDate(): Date
    {
        return self::date()->format('Y-m-d');
    }

    /**
     * Get validation rules for phone.
     */
    public static function phone(): SaudiPhone
    {
        return new SaudiPhone;
    }
}
