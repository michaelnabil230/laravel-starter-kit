<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Validation\Rule as BaseRule;
use libphonenumber\PhoneNumberUtil;

final class Rule extends BaseRule
{
    /**
     * Get validation rules for birth date
     *
     * @return array<array-key, string>
     */
    public static function birthDate(): array
    {
        return ['date', 'date_format:Y-m-d', 'before:'.now()->subYears(16)->format('Y-m-d')];
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
