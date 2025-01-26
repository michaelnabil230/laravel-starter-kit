<?php

declare(strict_types=1);

namespace App\Rules\ItemRules;

use App\Rules\ItemRules\Traits\TemporaryUploadMedia;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class HeightBetweenRule implements ValidationRule
{
    use TemporaryUploadMedia;

    public function __construct(
        protected int $minHeight = 0,
        protected int $maxHeight = 0
    ) {}

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

        $size = getimagesize($media->getPath());
        $actualHeight = type($size)->asArray()[1];

        $condition = $actualHeight >= $this->minHeight && $actualHeight <= $this->maxHeight;

        if (! $condition) {
            $fail('validation.height_not_between')->translate([
                'min' => $this->minHeight,
                'max' => $this->maxHeight,
            ]);
        }
    }
}
