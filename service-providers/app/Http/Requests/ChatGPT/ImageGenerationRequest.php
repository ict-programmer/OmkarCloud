<?php

namespace App\Http\Requests\ChatGPT;

use Illuminate\Foundation\Http\FormRequest;

class ImageGenerationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'model' => 'required|string',
            'prompt' => 'required|string',
            'n' => 'required|integer|min:1|max:10',
            'size' => 'required|string',
        ];
    }
}
