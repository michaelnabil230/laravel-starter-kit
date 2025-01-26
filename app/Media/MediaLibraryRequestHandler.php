<?php

declare(strict_types=1);

namespace App\Media;

use App\Media\Dto\MediaLibraryRequestItem;
use App\Media\Dto\PendingMediaItem;
use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

final class MediaLibraryRequestHandler
{
    /** @var array<array-key, string> */
    protected array $existingUuids;

    /** @param  Collection<array-key, MediaLibraryRequestItem>  $mediaLibraryRequestItems */
    protected function __construct(
        protected Model $model,
        protected Collection $mediaLibraryRequestItems,
        protected string $collectionName,
    ) {
        $this->existingUuids = $this->model->getMedia($collectionName)->pluck('uuid')->toArray(); // @phpstan-ignore-line
    }

    /** @param  Collection<array-key, MediaLibraryRequestItem>  $mediaLibraryRequestItems */
    public static function createForMediaLibraryRequestItems(
        Model $model,
        Collection $mediaLibraryRequestItems,
        string $collectionName
    ): self {
        return new self(
            $model,
            $mediaLibraryRequestItems,
            $collectionName,
        );
    }

    public function updateExistingMedia(): self
    {
        $this
            ->existingMediaLibraryRequestItems()
            ->each($this->handleExistingMediaLibraryRequestItem(...));

        return $this;
    }

    public function deleteObsoleteMedia(): self
    {
        $keepUuids = $this->mediaLibraryRequestItems->pluck('uuid')->toArray();

        $this->model->getMedia($this->collectionName) // @phpstan-ignore-line
            ->reject(fn (Media $media): bool => in_array($media->uuid, $keepUuids))
            ->each(fn (Media $media) => $media->delete());

        return $this;
    }

    /** @return Collection<array-key, PendingMediaItem> */
    public function getPendingMediaItems(): Collection
    {
        return $this
            ->newMediaLibraryRequestItems()
            ->map(fn (MediaLibraryRequestItem $mediaLibraryRequestItem): PendingMediaItem => new PendingMediaItem(
                $mediaLibraryRequestItem->uuid,
                $mediaLibraryRequestItem->name,
                $mediaLibraryRequestItem->order,
                $mediaLibraryRequestItem->customProperties,
                $mediaLibraryRequestItem->customHeaders,
                $mediaLibraryRequestItem->fileName,
            ));
    }

    /** @return Collection<array-key, MediaLibraryRequestItem> */
    protected function existingMediaLibraryRequestItems(): Collection
    {
        return $this
            ->mediaLibraryRequestItems
            ->filter(fn (MediaLibraryRequestItem $mediaLibraryRequestItem): bool => in_array($mediaLibraryRequestItem->uuid, $this->existingUuids));
    }

    /** @return Collection<array-key, MediaLibraryRequestItem> */
    protected function newMediaLibraryRequestItems(): Collection
    {
        return $this
            ->mediaLibraryRequestItems
            ->reject(fn (MediaLibraryRequestItem $mediaLibraryRequestItem): bool => in_array($mediaLibraryRequestItem->uuid, $this->existingUuids));
    }

    protected function handleExistingMediaLibraryRequestItem(MediaLibraryRequestItem $mediaLibraryRequestItem): void
    {
        $media = Media::findByUuid($mediaLibraryRequestItem->uuid);

        $media->update([
            'name' => $mediaLibraryRequestItem->name,
            'custom_properties' => $mediaLibraryRequestItem->customProperties,
            'order_column' => $mediaLibraryRequestItem->order,
        ]);
    }
}
