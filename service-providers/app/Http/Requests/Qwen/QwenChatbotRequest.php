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
            'conversation_history' => 'required|array|min:1|max:50',
            'conversation_history.*.role' => 'required|string',
            'conversation_history.*.content' => 'required|string',
            'temperature' => 'nullable|numeric|min:0|max:1',
            'max_tokens' => 'nullable|integer|min:1|max:4000',
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
            'conversation_history.min' => __('The conversation history must have at least 1 message.'),
            'conversation_history.max' => __('The conversation history may not have more than 50 messages.'),
            'conversation_history.*.role.required' => __('The conversation history.*.role field is required.'),
            'conversation_history.*.role.string' => __('The conversation history.*.role field must be a string.'),
            'conversation_history.*.content.required' => __('The conversation history.*.content field is required.'),
            'conversation_history.*.content.string' => __('The conversation history.*.content field must be a string.'),
            'temperature.numeric' => __('The temperature field must be numeric.'),
            'temperature.min' => __('The temperature must be at least 0.'),
            'temperature.max' => __('The temperature may not be greater than 1.'),
            'max_tokens.integer' => __('The max tokens field must be an integer.'),
            'max_tokens.min' => __('The max tokens must be at least 1.'),
            'max_tokens.max' => __('The max tokens may not be greater than 4000.'),
            'endpoint_interface.string' => __('The endpoint interface field must be a string.'),
        ];
    }
}
