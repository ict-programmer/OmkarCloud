<?php

namespace App\Http\Requests\Shutterstock;

use Illuminate\Foundation\Http\FormRequest;

class GetAudioRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'audio_id' => 'required|string|max:255',
        ];
    }
} 