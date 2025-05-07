<?php

namespace App\Http\Requests\ChatGPT;

use Illuminate\Foundation\Http\FormRequest;

class TextEmbeddingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'model' => 'required|string',
            'input' => 'required|string',
        ];
    }
}
