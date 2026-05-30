<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Fluent;
use Spatie\Activitylog\Models\Activity as BaseActivity;

final class ActivityLog extends BaseActivity
{
    /**
     * Revert the activity to restore the subject to its previous state.
     */
    public function revert(): void
    {
        match ($this->event) {
            'updated' => $this->restoreUpdatedSubject(),
            'deleted' => $this->restoreDeletedSubject(),
            default => null,
        };
    }

    /**
     * Filter the activities.
     *
     * @param  Builder<self>  $builder
     * @param  Fluent<string, mixed>  $data
     * @return Builder<self>
     */
    #[\Illuminate\Database\Eloquent\Attributes\Scope]
    protected function filter(Builder $builder, Fluent $data): Builder
    {
        return $builder
            ->when(
                $data->string('search')->value(),
                fn (Builder $builder, string $search): Builder => $builder->whereAny(['description', 'event'], 'Like', "%$search%")
            )
            ->orderBy(
                $data->get('order_by', 'created_at'),
                $data->get('order_by_direction', 'desc'),
            );
    }

    /**
     * Restore a soft-deleted subject.
     */
    protected function restoreDeletedSubject(): void
    {
        if (method_exists($this->subject, 'restore')) {
            $this->subject->restore();
        }
    }

    /**
     * Restore a updated subject.
     */
    protected function restoreUpdatedSubject(): void
    {
        $old = $this->attribute_changes['old'];

        unset($old['updated_at']);

        $this->subject->update($old);
    }
}
