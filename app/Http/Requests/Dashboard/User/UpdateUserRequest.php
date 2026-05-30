<?php

declare(strict_types=1);

namespace App\Http\Requests\Dashboard\User;

use App\Enums\AdminRole;
use App\Enums\Gender;
use App\Enums\Source;
use App\Models\Admin;
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
        /** @var Admin $admin */
        $admin = type(auth()->user())->as(Admin::class);

        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', Rule::phone(), Rule::unique(User::class)->ignore($this->route('user'))],
            'birth_date' => ['sometimes', 'nullable', Rule::birthDate()],
            'gender' => ['required', Rule::enum(Gender::class)],
            'source' => ['required', Rule::enum(Source::class)],
            'national_id' => [
                'sometimes',
                'nullable',
                Rule::requiredIf($admin->role === AdminRole::RECEPTION),
                Rule::unique(User::class)->ignore($this->route('user')),
            ],
            'is_old' => ['required', 'boolean'],
            'medical_question_answers' => ['sometimes', 'nullable', 'array'],
            'medical_question_answers.*' => ['sometimes', 'nullable', Rule::exists(MedicalAnswer::class, 'id')],
        ];
    }
}
