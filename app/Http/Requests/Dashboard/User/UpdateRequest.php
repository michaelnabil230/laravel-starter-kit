<?php

declare(strict_types=1);

namespace App\Http\Requests\Dashboard\User;

use App\Enums\Gender;
use App\Models\User;
use App\Rules\Rule;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateRequest extends FormRequest
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
            'email' => ['required', Rule::email()->default(), Rule::unique(User::class)->ignore($this->route('user'))],
            'phone' => ['required', 'phone', Rule::unique(User::class)->ignore($this->route('user'))],
            'phone_country' => Rule::isPhoneCountry(),
            'birth_date' => ['required', ...Rule::birthDate()],
            'gender' => ['required', Rule::enum(Gender::class)],
        ];
    }
}
