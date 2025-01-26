<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use App\Media\Support\DefaultAllowedExtensions;
use App\Rules\FileExtensionRule;
use App\Rules\Rule;
use Illuminate\Foundation\Http\FormRequest;

final class UploadRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $allowedExtensions = DefaultAllowedExtensions::all();

        return [
            'file' => [
                Rule::file()
                    ->max(config('media-library.max_file_size') / 1024)
                    ->types($allowedExtensions),
                new FileExtensionRule($allowedExtensions),
            ],
        ];
    }
}
