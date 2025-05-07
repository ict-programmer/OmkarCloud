<?php

namespace App\Http\Requests\Qwen;

use Illuminate\Foundation\Http\FormRequest;

class QwenChatbotRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'model' => 'required|string',
            'conversation_history' => 'required|array',
            'conversation_history.*.role' => 'required|string',
            'conversation_history.*.content' => 'required|string',
            'temperature' => 'nullable|numeric',
            'max_tokens' => 'nullable|integer',
            'endpoint_interface' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'model.required' => __('The model field is required.'),
            'model.string' => __('The model field must be a string.'),
            'conversation_history.required' => __('The conversation history field is required.'),
            'conversation_history.array' => __('The conversation history field must be an array.'),
            'conversation_history.*.role.required' => __('The conversation history.*.role field is required.'),
            'conversation_history.*.role.string' => __('The conversation history.*.role field must be a string.'),
            'conversation_history.*.content.required' => __('The conversation history.*.content field is required.'),
            'conversation_history.*.content.string' => __('The conversation history.*.content field must be a string.'),
            'temperature.numeric' => __('The temperature field must be numeric.'),
            'max_tokens.integer' => __('The max tokens field must be an integer.'),
            'endpoint_interface.string' => __('The endpoint interface field must be a string.'),
        ];
    }
}
