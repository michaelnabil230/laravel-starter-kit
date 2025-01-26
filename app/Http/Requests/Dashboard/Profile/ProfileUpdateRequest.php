<?php

declare(strict_types=1);

namespace App\Http\Requests\Dashboard\Profile;

use App\Models\Admin;
use App\Rules\Rule;
use Illuminate\Foundation\Http\FormRequest;

final class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $adminId = auth()->id();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', Rule::email()->default(), Rule::unique(Admin::class)->ignore($adminId)],
            'photo' => ['nullable', 'image'],
        ];
    }
}
