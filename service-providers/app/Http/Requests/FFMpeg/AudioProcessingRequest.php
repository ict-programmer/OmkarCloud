<?php

namespace App\Http\Requests\FFMpeg;

use Illuminate\Foundation\Http\FormRequest;

class AudioProcessingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file_link' => 'required|url',
            'bitrate' => 'required|string',
            'sample_rate' => 'required|integer|min:1',
            'channels' => 'required|integer|min:1|max:2',
        ];
    }
}
