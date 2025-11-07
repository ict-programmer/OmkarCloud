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
            'text' => 'required|string|min:10|max:10000',
            'text_length' => 'nullable|integer|min:1|max:1000',
            'max_tokens' => 'nullable|integer|min:1|max:2000',
            'temperature' => 'nullable|numeric|min:0|max:1',
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
            'text.min' => __('The text must be at least 10 characters.'),
            'text.max' => __('The text may not be greater than 10000 characters.'),
            'text_length.integer' => __('The text length field must be an integer.'),
            'text_length.min' => __('The text length must be at least 1.'),
            'text_length.max' => __('The text length may not be greater than 1000.'),
            'max_tokens.integer' => __('The max tokens field must be an integer.'),
            'max_tokens.min' => __('The max tokens must be at least 1.'),
            'max_tokens.max' => __('The max tokens may not be greater than 2000.'),
            'temperature.numeric' => __('The temperature field must be numeric.'),
            'temperature.min' => __('The temperature must be at least 0.'),
            'temperature.max' => __('The temperature may not be greater than 1.'),
            'endpoint_interface.string' => __('The endpoint interface field must be a string.'),            
        ];
    }
}
