<?php

namespace App\Http\Requests\FFMpeg;

use Illuminate\Foundation\Http\FormRequest;

class VideoProcessingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'input_file' => 'required|mimes:mp4,mov,avi,wmv,flv',
            'resolution' => 'required|string',
            'bitrate' => 'required|string',
            'frame_rate' => 'required|integer|min:1|max:120',
        ];
    }
}
