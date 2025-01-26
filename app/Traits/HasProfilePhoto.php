<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @property string $profile_photo_url
 */
trait HasProfilePhoto
{
    /**
     * Update the user's profile photo.
     *
     * @return array<string, string>
     */
    public function updateProfilePhoto(UploadedFile $photo): array
    {
        if ($previous = $this->photo) {
            Storage::delete($previous);
        }

        return [
            'photo' => (string) $photo->store('profile-photos'),
        ];
    }

    /**
     * Delete the user's profile photo.
     */
    public function deleteProfilePhoto(): void
    {
        $this->removeProfilePhotoFromDisk();
        $this->setNullInToProfilePhoto();
    }

    /**
     * Remove the user's profile photo from disk.
     */
    public function removeProfilePhotoFromDisk(): void
    {
        if (is_null($this->photo)) {
            return;
        }

        Storage::delete($this->photo);
    }

    /**
     * Set null user's profile photo path.
     */
    public function setProfilePhoto(UploadedFile $photo): void
    {
        $this->forceFill($this->updateProfilePhoto($photo))->save();
    }

    /**
     * Set null user's profile photo path.
     */
    public function setNullInToProfilePhoto(): void
    {
        $this->forceFill(['photo' => null])->save();
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * @return Attribute<string, never>
     */
    protected function profilePhotoUrl(): Attribute
    {
        return Attribute::get(fn () => $this->photo
            ? Storage::url($this->photo)
            : $this->defaultProfilePhotoUrl());
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     */
    protected function defaultProfilePhotoUrl(): string
    {
        $name = mb_trim(collect(explode(' ', $this->name))
            ->map(fn ($segment): string => mb_substr($segment, 0, 1))
            ->join(' '));

        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=f8fafc&background=0d9488';
    }
}
