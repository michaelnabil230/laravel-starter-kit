<?php

declare(strict_types=1);

namespace App\Rules\GroupRules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;

final readonly class MinItemsRule implements ValidationRule
{
    public function __construct(public int $minItemCount) {}

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $condition = (is_countable($value) ? count($value) : 0) >= $this->minItemCount;

        if (! $condition) {
            $fail('validation.min_items')->translateChoice($this->minItemCount, [
                'min' => $this->minItemCount,
            ]);
        }
    }
}
