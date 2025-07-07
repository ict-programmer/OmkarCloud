<?php

namespace App\Http\Requests\Qwen;

use Illuminate\Foundation\Http\FormRequest;

class QwenCodeGenerationRequest extends FormRequest
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
            'prompt' => 'required|string|min:1|max:10000',
            'max_tokens' => 'nullable|integer|min:1|max:2000',
            'temperature' => 'nullable|numeric|min:0|max:1',
            'endpoint_interface' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|url',
        ];
    }

    public function messages(): array
    {
        return [
            'model.required' => __('The model field is required.'),
            'model.string' => __('The model field must be a string.'),
            'prompt.required' => __('The prompt field is required.'),
            'prompt.string' => __('The prompt field must be a string.'),
            'prompt.min' => __('The prompt must be at least 1 character.'),
            'prompt.max' => __('The prompt may not be greater than 10000 characters.'),
            'max_tokens.integer' => __('The max tokens field must be an integer.'),
            'max_tokens.min' => __('The max tokens must be at least 1.'),
            'max_tokens.max' => __('The max tokens may not be greater than 2000.'),
            'temperature.numeric' => __('The temperature field must be numeric.'),
            'temperature.min' => __('The temperature must be at least 0.'),
            'temperature.max' => __('The temperature may not be greater than 1.'),
            'endpoint_interface.string' => __('The endpoint interface field must be a string.'),
            'attachments.array' => __('The attachments field must be an array.'),
            'attachments.*.url' => __('The attachments.*.file field must be a valid URL.'),
        ];
    }
}
