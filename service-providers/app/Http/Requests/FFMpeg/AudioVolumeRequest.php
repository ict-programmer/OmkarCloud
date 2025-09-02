<?php

namespace App\Http\Requests\FFMpeg;

use Illuminate\Foundation\Http\FormRequest;

class AudioVolumeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'input' => 'required|url',
            'volume_factor' => 'required|numeric|min:0|max:10',
        ];
    }
}
