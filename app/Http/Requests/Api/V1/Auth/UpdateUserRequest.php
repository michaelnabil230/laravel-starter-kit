<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Auth;

use App\Models\MedicalAnswer;
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
            'name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'email' => ['sometimes', 'nullable', 'email', Rule::unique(User::class)->ignore($userId)],
            'phone' => ['sometimes', 'nullable', Rule::phone(), Rule::unique(User::class)->ignore($userId)],
            'birth_date' => ['sometimes', 'nullable', Rule::birthDate()],
            'medical_question_answers' => ['sometimes', 'nullable', 'array'],
            'medical_question_answers.*' => ['sometimes', 'nullable', Rule::exists(MedicalAnswer::class, 'id')],
        ];
    }
}
