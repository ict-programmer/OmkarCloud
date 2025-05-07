<?php

namespace App\Http\Requests\ChatGPT;

use Illuminate\Foundation\Http\FormRequest;

class ChatCompletionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'model' => 'required|string',
            'messages' => 'required',
            'temperature' => 'required|numeric',
            'max_tokens' => 'required|int',
        ];
    }
}
