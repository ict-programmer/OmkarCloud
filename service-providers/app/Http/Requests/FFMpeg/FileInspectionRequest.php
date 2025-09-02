<?php

namespace App\Http\Requests\FFMpeg;

use Illuminate\Foundation\Http\FormRequest;

class FileInspectionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'input' => 'required|url',
        ];
    }

    public function messages(): array
    {
        return [
            'input.required' => 'Input media file URL is required.',
            'input.url' => 'Input must be a valid URL.',
        ];
    }
}
