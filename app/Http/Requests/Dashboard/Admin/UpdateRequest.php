<?php

declare(strict_types=1);

namespace App\Http\Requests\Dashboard\Admin;

use App\Enums\AdminRole;
use App\Models\Admin;
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
            'email' => ['required', Rule::email()->default(), Rule::unique(Admin::class)->ignore($this->route('admin'))],
            'phone' => ['required', 'phone', Rule::unique(Admin::class)->ignore($this->route('admin'))],
            'phone_country' => Rule::isPhoneCountry(),
            'role' => ['required', Rule::enum(AdminRole::class)],
        ];
    }
}
