<?php

namespace App\Http\Requests\ChatGPT;

use Illuminate\Foundation\Http\FormRequest;

class UiFieldExtractionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'image' => 'required|string',
        ];
    }
}
