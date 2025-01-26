<?php

declare(strict_types=1);

namespace App\Rules\ItemRules\Traits;

use App\Models\TemporaryUpload;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait TemporaryUploadMedia
{
    public function getTemporaryUploadMedia(string $uuid): ?Media
    {
        $temporaryUpload = TemporaryUpload::findByMediaUuidInCurrentSession($uuid);

        if (! $temporaryUpload instanceof TemporaryUpload) {
            return null;
        }

        return $temporaryUpload->getFirstMedia();
    }
}
