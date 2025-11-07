<?php

namespace App\Http\Requests\FFMpeg;

use Illuminate\Foundation\Http\FormRequest;

class ScaleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'input' => 'required|url',
            'resolution_target' => [
                'required',
                'string',
                'regex:/^\d+x\d+$|^(720p|1080p|1440p|2160p|4K|8K)$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'resolution_target.regex' => 'Resolution target must be in format "1920x1080" or use preset like "720p", "1080p", "1440p", "2160p", "4K", "8K".',
        ];
    }
}
