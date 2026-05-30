<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Dashboard;

use App\Rules\Rule;
use Illuminate\Foundation\Http\FormRequest;

final class UserPhoneVerificationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'otp_code' => ['required', Rule::numeric()->integer()->minDigits(4)->maxDigits(4)],
        ];
    }
}
