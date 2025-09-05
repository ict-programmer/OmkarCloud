<?php

namespace App\Http\Requests\Shutterstock;

use Illuminate\Foundation\Http\FormRequest;

class DownloadAudioRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'license_id' => 'required|string|max:255',
        ];
    }
} 