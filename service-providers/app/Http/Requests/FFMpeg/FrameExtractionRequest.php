<?php

namespace App\Http\Requests\FFMpeg;

use Illuminate\Foundation\Http\FormRequest;

class FrameExtractionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'input_file' => 'required|url',
            'frame_rate' => 'required|numeric|min:0.1|max:30',
        ];
    }
}
