<?php

declare(strict_types=1);

namespace App\Rules\ItemRules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator as ValidatorFacade;

final class AttributeRule implements ValidationRule
{
    /** @var array<array-key, mixed> */
    protected array $rules;

    /** @param  string[]|string  $rules */
    public function __construct(public string $attribute, string|array $rules)
    {
        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        $this->rules = [$this->shortAttributeName() => $rules];
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validator = ValidatorFacade::make(
            [$this->shortAttributeName() => $value],
            $this->rules
        );

        if (! $validator->passes()) {
            $fail($validator->messages()->first($this->shortAttributeName()));
        }
    }

    protected function shortAttributeName(): string
    {
        return str_replace('custom_properties.', '', $this->attribute);
    }
}
