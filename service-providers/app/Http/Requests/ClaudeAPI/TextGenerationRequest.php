<?php

namespace App\Http\Requests\ClaudeAPI;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TextGenerationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'prompt' => ['required', 'string', 'max:1000', 'min:1'],
            'max_tokens' => ['required', 'integer', 'min:1', 'max:5000'],
            'model' => ['nullable', 'string'],
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
            'prompt.required' => 'The prompt field is required to generate text.',
            'prompt.string' => 'The prompt must be a valid text string.',
            'prompt.max' => 'The prompt may not be greater than 1000 characters.',
            'prompt.min' => 'The prompt must be at least 1 character long.',
            'max_tokens.required' => 'The max_tokens field is required to specify response length.',
            'max_tokens.integer' => 'The max_tokens must be a whole number.',
            'max_tokens.min' => 'The max_tokens must be at least 1 token.',
            'max_tokens.max' => 'The max_tokens may not be greater than 5000 tokens.',
            'model.string' => 'The model must be a valid string.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'prompt' => 'text prompt',
            'max_tokens' => 'maximum tokens',
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
