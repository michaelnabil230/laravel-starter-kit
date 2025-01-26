<?php

declare(strict_types=1);

namespace App\Rules\GroupRules;

use App\Models\Media;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Spatie\MediaLibrary\Support\File;

final class MaxTotalSizeInKbRule implements ValidationRule
{
    public function __construct(protected int $maxTotalSizeInKb) {}

    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $uuids = collect($value) // @phpstan-ignore-line
            ->map(fn (array $uploadedItemAttributes) => $uploadedItemAttributes['uuid'])
            ->toArray();

        $media = Media::findWithTemporaryUploadInCurrentSession($uuids);

        $actualTotalSizeInKb = $media->totalSizeInBytes();

        $condition = $actualTotalSizeInKb <= ($this->maxTotalSizeInKb * 1024);

        if (! $condition) {
            $fail('validation.total_upload_size_too_high')->translate([
                'max' => File::getHumanReadableSize($this->maxTotalSizeInKb * 1024),
                'maxInKb' => $this->maxTotalSizeInKb,
                'actual' => File::getHumanReadableSize($actualTotalSizeInKb * 1024),
                'actualInKb' => $actualTotalSizeInKb,
            ]);
        }
    }
}
