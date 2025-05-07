<?php

namespace App\Http\Requests\Placid;

use Illuminate\Foundation\Http\FormRequest;

class VideoGenerationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'clips' => 'required|array',
            'clips.*.template_uuid' => 'required|string',
        ];
    }
}
