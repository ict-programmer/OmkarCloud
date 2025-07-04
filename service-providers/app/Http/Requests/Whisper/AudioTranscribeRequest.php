<?php

namespace App\Http\Requests\Whisper;

use Illuminate\Foundation\Http\FormRequest;

class AudioTranscribeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'link' => 'required|string',
            'language' => 'required|string',
            'prompt' => 'required|string',
        ];
    }
}
