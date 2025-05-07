<?php

namespace App\Http\Requests\Qwen;

use Illuminate\Foundation\Http\FormRequest;

class QwenTextSummarizationRequest extends FormRequest
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
            'text' => 'required|string',
            'text_length' => 'nullable|integer',
            'max_tokens' => 'nullable|integer',
            'temperature' => 'nullable|numeric',
            'endpoint_interface' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'model.required' => __('The model field is required.'),
            'model.string' => __('The model field must be a string.'),
            'text.required' => __('The text field is required.'),
            'text.string' => __('The text field must be a string.'),
            'text_length.integer' => __('The text length field must be an integer.'),
            'max_tokens.integer' => __('The max tokens field must be an integer.'),
            'temperature.numeric' => __('The temperature field must be numeric.'),
            'endpoint_interface.string' => __('The endpoint interface field must be a string.'),            
        ];
    }
}
