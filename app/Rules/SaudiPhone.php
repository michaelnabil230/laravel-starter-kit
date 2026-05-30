<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

final class SaudiPhone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) && ! is_numeric($value)) {
            $fail('validation.phone')->translate();

            return;
        }

        $normalized = $this->normalize((string) $value);
        $localDigits = $this->extractLocalDigits($normalized);

        if ($localDigits === null || ! $this->isValidLocalMobile($localDigits)) {
            $fail('validation.phone')->translate();
        }
    }

    /**
     * Normalize the phone number by removing spaces and dashes.
     */
    protected function normalize(string $raw): string
    {
        return (string) Str::of($raw)->replace([' ', '-'], '');
    }

    /**
     * Extract the local digits from the normalized phone number.
     */
    protected function extractLocalDigits(string $normalized): ?string
    {
        if (Str::startsWith($normalized, '+966')) {
            return Str::substr($normalized, 4);
        }

        if (Str::startsWith($normalized, '966')) {
            return Str::substr($normalized, 3);
        }

        if (Str::startsWith($normalized, '05')) {
            // Drop the leading zero to convert to 9-digit local form
            return Str::substr($normalized, 1);
        }

        if (Str::startsWith($normalized, '5')) {
            return $normalized;
        }

        return null;
    }

    /**
     * Validate if the local digits represent a valid Saudi mobile number.
     */
    protected function isValidLocalMobile(string $digits): bool
    {
        return Str::startsWith($digits, '5')
            && Str::length($digits) === 9
            && ctype_digit($digits);
    }
}
