<?php

declare(strict_types=1);

namespace App\Media\Dto;

use Illuminate\Support\Str;

final class MediaLibraryRequestItem
{
    /**
     * @param  array<string, mixed>  $customProperties
     * @param  array<string, mixed>  $customHeaders
     */
    protected function __construct(
        public string $uuid,
        public string $name,
        public int $order,
        public array $customProperties,
        public array $customHeaders,
        public ?string $fileName = null,
    ) {}

    /**
     * @param  array<string, mixed>  $properties
     */
    public static function fromArray(array $properties): self
    {
        $properties = collect($properties)
            ->keyBy(fn ($value, $key) => Str::snake($key));

        return new self(
            $properties['uuid'],
            $properties['name'] ?? '',
            $properties['order'] ?? 0,
            $properties['custom_properties'] ?? [],
            $properties['custom_headers'] ?? [],
            $properties['file_name'] ?? null,
        );
    }
}
