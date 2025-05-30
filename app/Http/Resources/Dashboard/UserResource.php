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
            'email' => $this->email,
            'phone' => $this->phone->formatE164(),
            'phone_country' => $this->phone_country,
            'photo' => $this->photo,
            'profile_photo_url' => $this->profile_photo_url,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender->value,
            'created_at' => $this->created_at->format('Y-m-d h:i:s A'),
            'updated_at' => $this->updated_at->format('Y-m-d h:i:s A'),
        ];
    }
}
