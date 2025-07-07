<?php

namespace App\Http\Requests\DeepSeek;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CodeCompletionRequest extends FormRequest
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
            'prompt' => ['required', 'string', 'min:1', 'max:1000'],
            'max_tokens' => ['required', 'integer', 'min:1', 'max:5000'],
            'temperature' => ['required', 'numeric', 'min:0', 'max:1'],
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|url'
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
            'model.in' => __('The selected model is invalid. Please choose either deepseek-chat.'),
            'prompt.required' => __('The prompt field is required.'),
            'prompt.string' => __('The prompt must be a string.'),
            'prompt.min' => __('The prompt must be at least 1 character.'),
            'prompt.max' => __('The prompt may not be greater than 1000 characters.'),
            'max_tokens.required' => __('The max_tokens field is required.'),
            'max_tokens.integer' => __('The max_tokens must be an integer.'),
            'max_tokens.min' => __('The max_tokens must be at least 1.'),
            'max_tokens.max' => __('The max_tokens may not be greater than 5000.'),
            'temperature.required' => __('The temperature field is required.'),
            'temperature.numeric' => __('The temperature must be a number.'),
            'temperature.min' => __('The temperature must be at least 0.'),
            'temperature.max' => __('The temperature may not be greater than 1.'),
            'attachments.array' => __('The attachments field must be an array.'),
            'attachments.*.url' => __('Each attachment must be a valid url.'), 
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
