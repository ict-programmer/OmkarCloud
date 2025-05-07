<?php

namespace App\Http\Requests\Placid;

use Illuminate\Foundation\Http\FormRequest;

class ImageGenerationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'template_uuid' => 'required|string',
            'layers' => 'required|array',
        ];
    }
}
