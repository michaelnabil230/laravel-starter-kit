<?php

declare(strict_types=1);

namespace App\Rules\ItemRules;

use App\Rules\ItemRules\Traits\TemporaryUploadMedia;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class WidthBetweenRule implements ValidationRule
{
    use TemporaryUploadMedia;

    public function __construct(
        protected int $minWidth = 0,
        protected int $maxWidth = 0
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
        $actualWidth = type($size)->asArray()[0];

        $condition = $actualWidth >= $this->minWidth && $actualWidth <= $this->maxWidth;

        if (! $condition) {
            $fail('validation.width_not_between')->translate([
                'min' => $this->minWidth,
                'max' => $this->maxWidth,
            ]);
        }
    }
}
