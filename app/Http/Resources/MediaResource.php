<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\Conversions\ConversionCollection;

/** @mixin \App\Models\Media */
final class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'url' => $this->getFullUrl(),
            'preview' => $this->getPreviewUrl(),
            'name' => $this->name,
            'file_name' => $this->file_name,
            'type' => $this->getType(),
            'mime_type' => $this->mime_type,
            'size' => $this->size,
            'human_readable_size' => $this->human_readable_size, // @phpstan-ignore-line
            'details' => $this->mediaDetails(),
            'conversions' => $this->when(
                ($this->isImage() || $this->isVideo()) && $this->getConversions() !== [],
                $this->getConversions()
            ),
        ];
    }

    /**
     * Get the generated conversions links.
     *
     * @return array<string, string>
     */
    protected function getConversions(): array
    {
        $results = [];

        foreach (array_keys($this->getGeneratedConversions()->toArray()) as $conversionName) {
            $conversion = ConversionCollection::createForMedia($this->resource)
                ->first(fn (Conversion $conversion): bool => $conversion->getName() === $conversionName);

            if ($conversion) {
                $results[$conversionName] = $this->getFullUrl($conversionName);
            }
        }

        return $results;
    }

    /**
     * Determine if the media type is video.
     */
    protected function isVideo(): bool
    {
        return $this->getType() === 'video';
    }

    /**
     * Determine if the media type is image.
     */
    protected function isImage(): bool
    {
        return $this->getType() === 'image';
    }

    /**
     * Determine if the media type is audio.
     */
    protected function isAudio(): bool
    {
        return $this->getType() === 'audio';
    }

    /**
     * Get the media type.
     */
    protected function getType(): string
    {
        return $this->getCustomProperty('type') ?: $this->type;
    }

    /**
     * Get the preview url.
     */
    protected function getPreviewUrl(): string
    {
        if ($this->getType() === 'image') {
            return $this->getFullUrl();
        }

        return 'https://cdn.jsdelivr.net/npm/laravel-file-uploader/dist/img/attach.png';
    }

    /**
     * Get the media details.
     *
     * @return array<array-key, mixed>
     */
    protected function mediaDetails(): array
    {
        return [
            $this->mergeWhen($this->isImage(), fn (): array => [
                'width' => $this->getCustomProperty('width'),
                'height' => $this->getCustomProperty('height'),
                'ratio' => (float) $this->getCustomProperty('ratio'),
            ]),
            'duration' => $this->when(
                $this->isVideo() || $this->isAudio(),
                fn (): float => (float) $this->getCustomProperty('duration'),
            ),
        ];
    }
}
