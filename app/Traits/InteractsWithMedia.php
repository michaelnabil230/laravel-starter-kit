<?php

declare(strict_types=1);

namespace App\Traits;

use App\Media\PendingMediaLibraryRequestHandler;
use Spatie\MediaLibrary\InteractsWithMedia as MediaLibraryInteractsWithMedia;

trait InteractsWithMedia
{
    use MediaLibraryInteractsWithMedia;

    /**
     * @param  array<array-key, array<array-key, mixed>>  $mediaLibraryRequestItems
     */
    public function addFromMediaLibraryRequest(?array $mediaLibraryRequestItems): PendingMediaLibraryRequestHandler
    {
        return new PendingMediaLibraryRequestHandler(
            $mediaLibraryRequestItems ?? [],
            $this,
            true
        );
    }

    /**
     * @param  array<array-key, array<array-key, mixed>>  $mediaLibraryRequestItems
     */
    public function syncFromMediaLibraryRequest(?array $mediaLibraryRequestItems): PendingMediaLibraryRequestHandler
    {
        return new PendingMediaLibraryRequestHandler(
            $mediaLibraryRequestItems ?? [],
            $this,
            false
        );
    }
}
