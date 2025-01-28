<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Auth;

use App\Models\User;
use App\Rules\Rule;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = auth('api')->id();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', Rule::email()->default(), Rule::unique(User::class)->ignore($userId)],
            'phone' => ['required', 'phone', Rule::unique(User::class)->ignore($userId)],
            'birth_date' => ['required', Rule::birthDate()],
            'phone_country' => Rule::isPhoneCountry(),
            'photo' => ['nullable', 'image'],
        ];
    }
}
