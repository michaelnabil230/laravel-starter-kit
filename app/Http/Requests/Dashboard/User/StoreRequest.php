<?php

declare(strict_types=1);

namespace App\Http\Requests\Dashboard\User;

use App\Enums\Gender;
use App\Models\User;
use App\Rules\Rule;
use Illuminate\Foundation\Http\FormRequest;

final class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', Rule::email()->default(), Rule::unique(User::class)],
            'phone' => ['required', 'phone', Rule::unique(User::class)],
            'phone_country' => Rule::isPhoneCountry(),
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'birth_date' => ['required', ...Rule::birthDate()],
            'gender' => ['required', Rule::enum(Gender::class)],
        ];
    }
}
