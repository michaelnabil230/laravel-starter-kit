<?php

declare(strict_types=1);

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\ActivityLog */
final class ActivityLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'log_name' => $this->log_name,
            'description' => $this->description,
            'subject_type' => $this->subject_type,
            'event' => $this->event,
            'attribute_changes' => $this->attribute_changes,
            'properties' => $this->properties,
            'causer' => $this->whenLoaded(
                'causer',
                fn () => AdminResource::make($this->causer),
            ),
            'created_at' => $this->created_at->format('Y-m-d h:i:s A'),
            'updated_at' => $this->updated_at->format('Y-m-d h:i:s A'),
        ];
    }
}
