<?php

namespace App\Http\Requests\FFMpeg;

use Illuminate\Foundation\Http\FormRequest;

class VideoTrimmingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'input_file' => 'required|mimes:mp4,mov,avi,wmv,flv',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s|after:start_time',
        ];
    }
}
