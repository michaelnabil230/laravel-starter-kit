<?php

declare(strict_types=1);

namespace App\Rules\GroupRules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class MaxItemsRule implements ValidationRule
{
    public function __construct(protected int $maxItemCount) {}

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $condition = (is_countable($value) ? count($value) : 0) <= $this->maxItemCount;

        if (! $condition) {
            $fail('validation.max_items')->translateChoice($this->maxItemCount, [
                'max' => $this->maxItemCount,
            ]);
        }
    }
}
