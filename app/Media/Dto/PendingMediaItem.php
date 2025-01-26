<?php

declare(strict_types=1);

namespace App\Media\Dto;

use App\Models\TemporaryUpload;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

final class PendingMediaItem
{
    public TemporaryUpload $temporaryUpload;

    /**
     * @param  array<array-key, string>  $customProperties
     * @param  array<array-key, string>  $customHeaders
     */
    public function __construct(
        string $uuid,
        public string $name,
        public int $order,
        public array $customProperties,
        public array $customHeaders,
        public ?string $fileName = null
    ) {
        throw_if(
            ! ($temporaryUpload = TemporaryUpload::findByMediaUuidInCurrentSession($uuid)) instanceof TemporaryUpload,
            new Exception('invalid uuid'),
        );

        $this->temporaryUpload = $temporaryUpload;
    }

    /**
     * @param  array<array-key, mixed>  $pendingMediaItems
     * @return Collection<array-key, PendingMediaItem>
     */
    public static function createFromArray(array $pendingMediaItems): Collection
    {
        return collect($pendingMediaItems)
            ->map(fn (array $uploadAttributes): PendingMediaItem => new self(
                $uploadAttributes['uuid'],
                $uploadAttributes['name'] ?? '',
                $uploadAttributes['order'] ?? 0,
                $uploadAttributes['custom_properties'] ?? [],
                $uploadAttributes['file_name'] ?? null,
            ));
    }

    /**
     * @return array<array-key, mixed>
     */
    public function toArray(): array
    {
        $media = $this->temporaryUpload->getFirstMedia();

        return [
            'uuid' => $media->uuid,
            'name' => $this->name,
            'order' => $this->order,
            'custom_properties' => $this->customProperties,
            'size' => $media->size,
            'mime' => $media->mime_type,
        ];
    }

    /**
     * @param  array<array-key, mixed>  $customPropertyNames
     * @return array<array-key, mixed>
     */
    public function getCustomProperties(array $customPropertyNames): array
    {
        if ($customPropertyNames === []) {
            return $this->customProperties;
        }

        return collect($customPropertyNames)
            ->filter(fn (string $customProperty) => Arr::has($this->customProperties, $customProperty))
            ->mapWithKeys(fn ($name) => [$name => Arr::get($this->customProperties, $name)])
            ->toArray();
    }
}
