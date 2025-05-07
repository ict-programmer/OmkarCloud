<?php

namespace App\Http\Requests\Gemini;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CodeGenerationRequest extends FormRequest
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
            'model' => ['required', 'in:gemini-pro,gemini-ultra,gemini-1.5-pro'],
            'prompt' => ['required', 'string', 'max:1000'],
            'max_tokens' => ['required', 'integer', 'min:1', 'max:5000'],
            'temperature' => ['required', 'numeric', 'between:0,1'],
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|file|max:30720' // 30MB
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
            'model.in' => __('The selected model is invalid. Please choose either gemini-pro or gemini-ultra.'),
            'prompt.required' => __('The prompt field is required.'),
            'prompt.string' => __('The prompt must be a string.'),
            'prompt.max' => __('The prompt may not be greater than 1000 characters.'),
            'max_tokens.required' => __('The max_tokens field is required.'),
            'max_tokens.integer' => __('The max_tokens must be an integer.'),
            'max_tokens.min' => __('The max_tokens must be at least 1.'),
            'max_tokens.max' => __('The max_tokens may not be greater than 5000.'),
            'temperature.required' => __('The temperature field is required.'),
            'temperature.numeric' => __('The temperature must be a number.'),
            'temperature.between' => __('The temperature must be between 0 and 1.'),
            'attachments.array' => 'The attachments field must be an array.',
            'attachments.*.file' => 'Each attachment must be a valid file.',
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
