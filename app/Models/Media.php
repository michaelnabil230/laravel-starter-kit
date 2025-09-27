<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Session;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media as Model;

final class Media extends Model
{
    /**
     * @param  string[]  $uuids
     * @return MediaCollection<int, self>
     */
    public static function findWithTemporaryUploadInCurrentSession(array $uuids): MediaCollection
    {
        return self::query()
            ->whereIn('uuid', $uuids)
            ->whereHasMorph(
                'model',
                [TemporaryUpload::class],
                fn (Builder $builder) => $builder->where('session_id', Session::getId())
            )
            ->get();
    }

    /**
     * Get the temporaryUpload that owns the Media
     *
     * @return BelongsTo<TemporaryUpload, covariant $this>
     */
    public function temporaryUpload(): BelongsTo
    {
        return $this->belongsTo(TemporaryUpload::class);
    }
}
