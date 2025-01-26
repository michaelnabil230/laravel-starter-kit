<?php

declare(strict_types=1);

namespace App\Models;

use App\Exceptions\CouldNotAddUpload;
use App\Exceptions\TemporaryUploadDoesNotBelongToCurrentSession;
use App\Traits\InteractsWithMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Session;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

final class TemporaryUpload extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'session_id',
    ];

    /**
     * Find a TemporaryUpload instance by the associated media UUID in the current session.
     */
    public static function findByMediaUuidInCurrentSession(?string $mediaUuid): ?self
    {
        if (! ($temporaryUpload = self::findByMediaUuid($mediaUuid)) instanceof self) {
            return null;
        }

        if (
            config()->boolean('media-library.enable_temporary_uploads_session_affinity', true)
            && $temporaryUpload->session_id !== Session::getId()
        ) {
            return null;
        }

        return $temporaryUpload;
    }

    /**
     * Find a TemporaryUpload instance by the associated media UUID.
     */
    public static function findByMediaUuid(?string $mediaUuid): ?self
    {
        /** @var ?Media $media */
        $media = Media::query()
            ->where('uuid', $mediaUuid)
            ->first();

        if (! $media) {
            return null;
        }

        $temporaryUpload = $media->model;

        if (! $temporaryUpload instanceof self) {
            return null;
        }

        return $temporaryUpload;
    }

    /**
     * Create a new TemporaryUpload for a given file.
     *
     * @throws CouldNotAddUpload
     */
    public static function createForFile(
        UploadedFile $uploadedFile,
        string $sessionId,
        string $uuid,
        string $name
    ): self {
        /** @var self $temporaryUpload */
        $temporaryUpload = self::create([
            'session_id' => $sessionId,
        ]);

        throw_if(
            self::findByMediaUuid($uuid) instanceof self,
            CouldNotAddUpload::uuidAlreadyExists(),
        );

        $temporaryUpload
            ->addMedia($uploadedFile)
            ->setName($name)
            ->withProperties(['uuid' => $uuid])
            ->toMediaCollection('default', config('media-library.disk_name'));

        return $temporaryUpload->fresh();
    }

    /**
     * Create a new TemporaryUpload for a given path file.
     *
     * @throws CouldNotAddUpload
     */
    public static function createForPathFile(
        string $path,
        ?string $disk,
        string $sessionId,
        string $uuid,
        string $name,
    ): self {
        /** @var self $temporaryUpload */
        $temporaryUpload = self::create([
            'session_id' => $sessionId,
        ]);

        throw_if(
            self::findByMediaUuid($uuid) instanceof self,
            CouldNotAddUpload::uuidAlreadyExists(),
        );

        $temporaryUpload
            ->addMediaFromDisk($path, $disk)
            ->setName($name)
            ->withProperties(['uuid' => $uuid])
            ->toMediaCollection('default', config('media-library.disk_name'));

        return $temporaryUpload->fresh();
    }

    /**
     * Scope a query to only include old temporary uploads.
     *
     * @param  Builder<self>  $builder
     * @return Builder<self>
     */
    public function scopeOld(Builder $builder): Builder
    {
        return $builder->where('created_at', '<=', now()->subDay()->toDateTimeString());
    }

    /**
     * Register the media conversions for the model.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        if (! config()->boolean('media-library.generate_thumbnails_for_temporary_uploads')) {
            return;
        }

        $this // @phpstan-ignore-line
            ->addMediaConversion('preview')
            ->fit(Fit::Crop, 300, 300)
            ->nonQueued();
    }

    /**
     * Move the media to the given HasMedia instance.
     */
    public function moveMedia(HasMedia $hasMedia, string $collectionName, string $diskName, string $fileName): Media
    {
        throw_if(
            config()->boolean('media-library.enable_temporary_uploads_session_affinity', true) && $this->session_id !== Session::getId(),
            TemporaryUploadDoesNotBelongToCurrentSession::create(),
        );

        $media = $this->getFirstMedia();

        $temporaryUploadModel = $media->model;
        $uuid = $media->uuid;

        $newMedia = $media->move($hasMedia, $collectionName, $diskName, $fileName);

        $temporaryUploadModel->delete();

        $newMedia->update(['uuid' => $uuid]);

        return $newMedia;
    }
}
