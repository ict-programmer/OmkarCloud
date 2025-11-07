<?php

namespace App\Http\Requests\FFMpeg;

use Illuminate\Foundation\Http\FormRequest;

class LoudnessNormalizationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file_link' => 'required|url',
            'target_lufs' => 'nullable|numeric|between:-50,0',
            'lra' => 'nullable|numeric|between:1,20',
            'tp' => 'nullable|numeric|between:-6,0',
        ];
    }
}
