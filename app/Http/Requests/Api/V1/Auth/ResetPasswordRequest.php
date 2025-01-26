<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Auth;

use App\Rules\Rule;
use Illuminate\Foundation\Http\FormRequest;

final class ResetPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', Rule::email()->default(), 'string', 'exists:password_reset_codes,email'],
            'code' => ['required', 'string', 'exists:password_reset_codes,code'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.exists' => __('passwords.code_is_invalid'),
        ];
    }
}
