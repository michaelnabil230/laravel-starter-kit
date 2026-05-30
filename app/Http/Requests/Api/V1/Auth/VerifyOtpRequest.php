<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Auth;

use App\Enums\DeviceType;
use App\Models\User;
use App\Rules\Rule;
use Illuminate\Foundation\Http\FormRequest;

final class VerifyOtpRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => ['required', Rule::phone(), Rule::exists(User::class)],
            'otp_code' => ['required', Rule::numeric()->integer()->minDigits(4)->maxDigits(4)],
            'fcm_token' => ['nullable', 'sometimes'],
            'device_type' => ['nullable', 'sometimes', Rule::enum(DeviceType::class)],
        ];
    }
}
