<?php

declare(strict_types=1);

namespace App\Rules\ItemRules;

use App\Rules\ItemRules\Traits\TemporaryUploadMedia;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Spatie\MediaLibrary\Support\File;

final class MaxItemSizeInKbRule implements ValidationRule
{
    use TemporaryUploadMedia;

    public function __construct(protected int $maxSizeInKb) {}

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

        $actualSizeInBytes = $media->size;

        $condition = $actualSizeInBytes <= ($this->maxSizeInKb * 1024);

        if (! $condition) {
            $fail('validation.file_too_big')->translate([
                'max' => File::getHumanReadableSize($this->maxSizeInKb * 1024),
                'maxInKb' => $this->maxSizeInKb,
                'actual' => File::getHumanReadableSize($actualSizeInBytes),
                'actualInKb' => round($actualSizeInBytes / 1024),
            ]);
        }
    }
}
