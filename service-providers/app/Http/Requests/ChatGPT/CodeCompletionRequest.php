<?php

namespace App\Http\Requests\ChatGPT;

use Illuminate\Foundation\Http\FormRequest;

class CodeCompletionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'model' => 'required|string',
            'description' => 'required|string',
            'temperature' => 'required|numeric|between:0,1',
            'max_tokens' => 'required|integer|min:1',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|file'
        ];
    }

    public function messages(): array
    {
        return [
            'model.required' => __('The model field is required.'),
            'description.required' => __('The description field is required.'),
            'description.string' => __('The description must be a string.'),
            'temperature.required' => __('The temperature field is required.'),
            'temperature.numeric' => __('The temperature must be a number.'),
            'temperature.between' => __('The temperature must be between 0 and 1.'),
            'max_tokens.required' => __('The max_tokens field is required.'),
            'max_tokens.integer' => __('The max_tokens must be an integer.'),
            'max_tokens.min' => __('The max_tokens must be at least 1.'),
            'attachments.array' => __('The attachments field must be an array.'),
            'attachments.*.file' => __('Each attachment must be a valid file.'),
        ];
    }
}
