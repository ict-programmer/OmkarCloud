<?php

namespace App\Http\Requests\FFMpeg;

use Illuminate\Foundation\Http\FormRequest;

class ThumbnailRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'input' => 'required|url',
            'timestamp' => 'required|string|regex:/^\d{2}:\d{2}:\d{2}(\.\d+)?$/',
        ];
    }

    public function messages(): array
    {
        return [
            'input.required' => __('Input video file URL is required.'),
            'input.url' => __('Input must be a valid URL.'),
            'timestamp.required' => __('Timestamp is required.'),
            'timestamp.regex' => __('Timestamp must be in format HH:MM:SS or HH:MM:SS.MS (e.g., 00:01:30 or 00:01:30.5)'),
        ];
    }
}
