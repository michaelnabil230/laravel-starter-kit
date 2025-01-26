<?php

declare(strict_types=1);

namespace App\Rules\GroupRules;

use App\Models\Media;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Spatie\MediaLibrary\Support\File;

final readonly class MinTotalSizeInKbRule implements ValidationRule
{
    public function __construct(public int $minTotalSizeInKb) {}

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

        $actualTotalSizeInBytes = $media->totalSizeInBytes();

        $condition = $actualTotalSizeInBytes >= ($this->minTotalSizeInKb * 1024);

        if (! $condition) {
            $fail('validation.total_upload_size_too_low')->translate([
                'min' => File::getHumanReadableSize($this->minTotalSizeInKb * 1024),
                'minInKb' => $this->minTotalSizeInKb,
                'actual' => File::getHumanReadableSize(round($actualTotalSizeInBytes / 1024)),
                'actualTotalSizeInKb' => round($actualTotalSizeInBytes / 1024),
            ]);
        }
    }
}
