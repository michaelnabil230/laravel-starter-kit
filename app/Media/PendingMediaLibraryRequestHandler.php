<?php

declare(strict_types=1);

namespace App\Media;

use App\Media\Dto\MediaLibraryRequestItem;
use App\Media\Dto\PendingMediaItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\MediaCollections\FileAdder;

final class PendingMediaLibraryRequestHandler
{
    /** @var Collection<array-key, MediaLibraryRequestItem> */
    protected Collection $mediaLibraryRequestItems;

    /** @var array<array-key, string[]>|null */
    protected ?array $processCustomProperties = null;

    /** @var array<array-key, string>|null */
    protected ?array $customHeaders = null;

    /**
     * @param  array<array-key, array<array-key, mixed>>  $mediaLibraryRequestItems
     */
    public function __construct(
        array $mediaLibraryRequestItems,
        protected Model $model,
        protected bool $preserveExisting,
    ) {
        $this->mediaLibraryRequestItems = collect($mediaLibraryRequestItems)
            ->map(MediaLibraryRequestItem::fromArray(...));
    }

    public function usingName(string|callable $mediaName): self
    {
        if (is_string($mediaName)) {
            return $this->usingName(fn (): string => $mediaName);
        }

        $callable = $mediaName;

        $this->mediaLibraryRequestItems->each(function (MediaLibraryRequestItem $mediaLibraryRequestItem) use ($callable): void {
            $name = $callable($mediaLibraryRequestItem);

            $mediaLibraryRequestItem->name = $name;
        });

        return $this;
    }

    public function usingFileName(string|callable $fileName): self
    {
        if (is_string($fileName)) {
            return $this->usingFileName(fn (): string => $fileName);
        }

        $callable = $fileName;

        $this->mediaLibraryRequestItems->each(function (MediaLibraryRequestItem $mediaLibraryRequestItem) use ($callable): void {
            $fileName = $callable($mediaLibraryRequestItem);

            $mediaLibraryRequestItem->fileName = $fileName;
        });

        return $this;
    }

    /**
     * @param  array<array-key, string>  ...$customPropertyNames
     */
    public function withCustomProperties(...$customPropertyNames): self
    {
        $this->processCustomProperties = $customPropertyNames;

        return $this;
    }

    /**
     * @param  array<array-key, string>  $customHeaders
     */
    public function addCustomHeaders(array $customHeaders): self
    {
        $this->customHeaders = $customHeaders;

        return $this;
    }

    public function toMediaLibrary(string $collectionName = 'default', string $diskName = ''): void
    {
        $this->toMediaCollection($collectionName, $diskName);
    }

    public function toMediaCollection(string $collectionName = 'default', string $diskName = ''): void
    {
        $mediaLibraryRequestHandler = MediaLibraryRequestHandler::createForMediaLibraryRequestItems(
            $this->model,
            $this->mediaLibraryRequestItems,
            $collectionName,
        )->updateExistingMedia();

        if (! $this->preserveExisting) {
            $mediaLibraryRequestHandler->deleteObsoleteMedia();
        }

        $mediaLibraryRequestHandler
            ->getPendingMediaItems()
            ->each(function (PendingMediaItem $pendingMediaItem) use ($diskName, $collectionName): void {
                $fileAdder = $this->createForPendingMedia($this->model, $pendingMediaItem);

                if (! is_null($this->processCustomProperties)) {
                    $fileAdder->withCustomProperties($pendingMediaItem->getCustomProperties($this->processCustomProperties));
                }

                if (! is_null($this->customHeaders)) {
                    $fileAdder = $fileAdder->addCustomHeaders($this->customHeaders);
                }

                if (! is_null($pendingMediaItem->fileName)) {
                    $fileAdder->setFileName($pendingMediaItem->fileName);
                }

                $fileAdder->toMediaCollection($collectionName, $diskName);
            });
    }

    public function createForPendingMedia(Model $model, PendingMediaItem $pendingMediaItem): FileAdder
    {
        /** @var FileAdder $fileAdder */
        $fileAdder = app(FileAdder::class);

        return $fileAdder
            ->setSubject($model)
            ->setFile($pendingMediaItem->temporaryUpload)
            ->setName($pendingMediaItem->name)
            ->setOrder($pendingMediaItem->order);
    }
}
