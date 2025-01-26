<?php

declare(strict_types=1);

namespace App\Rules\ItemRules;

use App\Rules\ItemRules\Traits\TemporaryUploadMedia;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Arr;

final class MimeTypeRule implements ValidationRule
{
    use TemporaryUploadMedia;

    /** @var array<array-key, string> */
    protected array $allowedMimeTypes;

    /** @param  string[]|string  $allowedMimeTypes */
    public function __construct(string|array $allowedMimeTypes)
    {
        $this->allowedMimeTypes = Arr::wrap($allowedMimeTypes);
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $condition = false;

        if (! ($media = $this->getTemporaryUploadMedia($value['uuid'])) instanceof \App\Models\Media) {
            $condition = true;
        }

        $condition = in_array($media->mime_type, $this->allowedMimeTypes);

        if (! $condition) {
            $fail('validation.mime')->translate([
                'mimes' => implode(', ', $this->allowedMimeTypes),
            ]);
        }
    }
}
