<?php

namespace App\Http\Requests\FFMpeg;

use Illuminate\Foundation\Http\FormRequest;

class AudioFadesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'input' => 'required|url',
            'fade_in_duration' => 'nullable|numeric|min:0|max:60',
            'fade_out_duration' => 'nullable|numeric|min:0|max:60',
        ];
    }

    public function messages(): array
    {
        return [
            'fade_in_duration.min' => 'Fade in duration must be at least 0 seconds.',
            'fade_in_duration.max' => 'Fade in duration cannot exceed 60 seconds.',
            'fade_out_duration.min' => 'Fade out duration must be at least 0 seconds.',
            'fade_out_duration.max' => 'Fade out duration cannot exceed 60 seconds.',
        ];
    }
}
