<?php

namespace App\Http\Requests\DeepSeek;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChatCompletionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'model' => ['required', 'in:deepseek-chat'],
            'messages' => ['required', 'array', 'min:1', 'max:50'],
            'messages.*.role' => ['required', 'string', 'in:system,user,assistant'],
            'messages.*.content' => ['required', 'string'],
            'max_tokens' => ['required', 'integer', 'min:1', 'max:4000'],
            'temperature' => ['required', 'numeric', 'min:0', 'max:1'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'model.required' => __('The model field is required.'),
            'model.in' => __('The selected model is invalid. It must be deepseek-chat.'),
            'messages.required' => __('The messages array is required'),
            'messages.array' => __('The messages must be an array.'),
            'messages.min' => __('The messages must have at least 1 message.'),
            'messages.max' => __('The messages may not have more than 50 messages.'),
            'messages.*.role.required' => __('Each message must have a role'),
            'messages.*.role.string' => __('Each message role must be a string.'),
            'messages.*.role.in' => __('Role must be one of: system, user, assistant'),
            'messages.*.content.required' => __('Each message must have content'),
            'messages.*.content.string' => __('Each message content must be a string.'),
            'max_tokens.required' => __('The max_tokens field is required.'),
            'max_tokens.integer' => __('The max_tokens field must be an integer.'),
            'max_tokens.min' => __('The max_tokens must be at least 1.'),
            'max_tokens.max' => __('The max_tokens may not be greater than 4000.'),
            'temperature.required' => __('The temperature field is required.'),
            'temperature.numeric' => __('The temperature field must be a number.'),
            'temperature.min' => __('The temperature must be at least 0.'),
            'temperature.max' => __('The temperature may not be greater than 1.'),
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }
}
