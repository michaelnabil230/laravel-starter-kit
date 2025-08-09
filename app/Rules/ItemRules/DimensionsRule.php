<?php

declare(strict_types=1);

namespace App\Rules\ItemRules;

use App\Rules\ItemRules\Traits\TemporaryUploadMedia;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

final class DimensionsRule implements ValidationRule
{
    use TemporaryUploadMedia;

    public function __construct(
        protected int $requiredWidth = 0,
        protected int $requiredHeight = 0
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

        $size = type(getimagesize($media->getPath()))->asArray();
        $actualWidth = $size[0];
        $actualHeight = $size[1];

        if ($this->requiredWidth && $this->requiredHeight) {
            $condition = $actualWidth === $this->requiredWidth && $actualHeight === $this->requiredHeight;
        }

        if ($this->requiredWidth !== 0) {
            $condition = $actualWidth === $this->requiredWidth;
        }

        if ($this->requiredHeight !== 0) {
            $condition = $actualHeight === $this->requiredHeight;
        }

        if (! $condition) {
            $massage = match (true) {
                $this->requiredWidth && $this->requiredHeight => 'validation.incorrect_dimensions.both',
                $this->requiredWidth !== 0 => 'validation.incorrect_dimensions.width',
                $this->requiredHeight !== 0 => 'validation.incorrect_dimensions.height',
                default => null,
            };

            if ($massage === null) {
                return;
            }

            $fail($massage)->translate([
                'width' => $this->requiredWidth,
                'height' => $this->requiredHeight,
            ]);
        }
    }
}
