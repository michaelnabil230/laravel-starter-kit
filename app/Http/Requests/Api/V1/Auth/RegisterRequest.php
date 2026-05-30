<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Auth;

use App\Enums\Gender;
use App\Models\User;
use App\Rules\Rule;
use Illuminate\Foundation\Http\FormRequest;

final class RegisterRequest extends FormRequest
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
            'email' => ['sometimes', 'nullable', 'email', Rule::unique(User::class)],
            'phone' => ['required', Rule::phone(), Rule::unique(User::class)],
            'birth_date' => ['required', Rule::birthDate()],
            'gender' => ['required', Rule::enum(Gender::class)],
            'national_id' => ['required', Rule::unique(User::class)],
        ];
    }
}
