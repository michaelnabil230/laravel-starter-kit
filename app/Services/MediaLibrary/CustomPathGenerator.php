<?php

declare(strict_types=1);

namespace App\Services\MediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;

final class CustomPathGenerator extends DefaultPathGenerator
{
    /*
     * Get a unique base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {
        $prefix = config('media-library.prefix', '');

        if ($prefix !== '') {
            return "$prefix/$media->model_id";
        }

        return (string) $media->model_id;
    }
}
