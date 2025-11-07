<?php

namespace App\Http\Requests\FFMpeg;

use Illuminate\Foundation\Http\FormRequest;

class AudioOverlayRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'background_track' => 'required|url',
            'overlay_track' => 'required|url',
            'output_format' => 'required|string|in:mp3,wav,flac,aac,ogg,m4a,wma',
        ];
    }
}
