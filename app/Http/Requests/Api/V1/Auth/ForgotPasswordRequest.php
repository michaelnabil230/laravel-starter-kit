<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Auth;

use App\Rules\Rule;
use Illuminate\Foundation\Http\FormRequest;

final class ForgotPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', Rule::email()->default(), 'exists:users,email'],
        ];
    }
}
