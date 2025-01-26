<?php

declare(strict_types=1);

namespace App\Http\Requests\Dashboard;

use App\Traits\ValidatesMedia;
use Illuminate\Foundation\Http\FormRequest;

final class ImportRequest extends FormRequest
{
    use ValidatesMedia;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'media' => ['required', $this->validateSingleMedia()->mime(['xlsx', 'xls'])],
        ];
    }
}
