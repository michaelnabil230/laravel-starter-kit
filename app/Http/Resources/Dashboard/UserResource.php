<?php

declare(strict_types=1);

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
final class UserResource extends JsonResource
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
            'name' => $this->name,
            'phone' => $this->phone,
            'birth_date' => $this->birth_date,
            'age' => $this->age,
            'gender' => $this->gender->value,
            'last_login_at' => $this->last_login_at?->format('Y-m-d h:i:s A'),
            'device_type' => $this->device_type?->value,
            'is_phone_verified' => $this->is_phone_verified,
            'created_at' => $this->created_at->format('Y-m-d h:i:s A'),
            'updated_at' => $this->updated_at->format('Y-m-d h:i:s A'),
        ];
    }
}
