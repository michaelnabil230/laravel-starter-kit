<?php

declare(strict_types=1);

namespace App\Http\Resources\V1\Notification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \Illuminate\Notifications\DatabaseNotification */
final class NotificationResource extends JsonResource
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
            'type' => str($this->type)->classBasename()->camel(),
            'data' => $this->data,
            'created_at' => $this->created_at,
        ];
    }
}
