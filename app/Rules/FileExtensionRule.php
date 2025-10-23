<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class FileExtensionRule implements ValidationRule
{
    /** @var string[] */
    protected array $validExtensions = [];

    /** @param string[]  $validExtensions */
    public function __construct(array $validExtensions = [])
    {
        $this->validExtensions = array_map(
            mb_strtolower(...),
            $validExtensions,
        );
    }

    /**
     * Run the validation rule.
     *
     * @param  \Illuminate\Http\UploadedFile  $value
     * @param  Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! in_array(mb_strtolower($value->getClientOriginalExtension()), $this->validExtensions, true)) {
            $fail('validation.mime')->translate([
                'mimes' => implode(', ', $this->validExtensions),
            ]);
        }
    }
}
