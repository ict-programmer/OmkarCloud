<?php

namespace App\Http\Requests\FFMpeg;

use Illuminate\Foundation\Http\FormRequest;

class AudioProcessingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'input_file' => 'required|mimes:mp3,wav,ogg,m4a,aac',
            'bitrate' => 'required|string',
            'sample_rate' => 'required|integer|min:1',
            'channels' => 'required|integer|min:1|max:2',
        ];
    }
}
