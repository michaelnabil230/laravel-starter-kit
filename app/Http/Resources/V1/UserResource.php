<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Contracts\Auth\MustVerifyEmail;
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
            'phone' => str_replace('+', '', $this->phone->formatE164()),
            'phone_country' => $this->phone_country,
            'email_verified' => $this instanceof MustVerifyEmail ? $this->hasVerifiedEmail() : false,
            'profile_photo_url' => $this->profile_photo_url,
            'gender' => $this->gender->value,
            'birth_date' => $this->birth_date,
            'updated_at' => $this->updated_at,
        ];
    }
}
