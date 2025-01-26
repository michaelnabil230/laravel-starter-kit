<?php

declare(strict_types=1);

namespace App\Rules\ItemRules;

use App\Rules\ItemRules\Traits\TemporaryUploadMedia;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Arr;
use Symfony\Component\Mime\MimeTypes;

final class ExtensionRule implements ValidationRule
{
    use TemporaryUploadMedia;

    /** @var array<array-key, string> */
    protected array $allowedExtensions;

    /** @param  string[]|string  $allowedExtensions */
    public function __construct(string|array $allowedExtensions)
    {
        $this->allowedExtensions = Arr::wrap($allowedExtensions);
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

        if (empty($media->mime_type)) {
            $extension = pathinfo($media->file_name, PATHINFO_EXTENSION);

            $condition = in_array($extension, $this->allowedExtensions);
        }

        $actualExtensions = (new MimeTypes)->getExtensions($media->mime_type);

        $condition = array_intersect($actualExtensions, $this->allowedExtensions) !== [];

        if (! $condition) {
            $fail('validation.extension')->translate([
                'extensions' => implode(', ', $this->allowedExtensions),
            ]);
        }
    }
}
