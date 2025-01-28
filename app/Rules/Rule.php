<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Validation\Rule as BaseRule;
use Illuminate\Validation\Rules\Date;
use libphonenumber\PhoneNumberUtil;

final class Rule extends BaseRule
{
    /**
     * Get validation rules for birth date
     */
    public static function birthDate(): Date
    {
        return self::date()
            ->format('Y-m-d')
            ->before(now()->subYears(16));
    }

    /**
     * Validate if the phone country is supported
     *
     * @return array<array-key, \Illuminate\Validation\Rules\In|string>
     */
    public static function isPhoneCountry(): array
    {
        return [
            'required',
            'string',
            self::in(PhoneNumberUtil::getInstance()->getSupportedRegions()),
        ];
    }
}
