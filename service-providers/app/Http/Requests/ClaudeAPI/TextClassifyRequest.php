<?php

namespace App\Http\Requests\ClaudeAPI;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TextClassifyRequest extends FormRequest
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
            'text' => ['required', 'string', 'min:5', 'max:5000'],
            'categories' => ['required', 'string', 'min:3', 'max:500'],
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
            'text.required' => 'The text field is required to perform classification.',
            'text.string' => 'The text must be a valid text string.',
            'text.min' => 'The text must be at least 5 characters long for meaningful classification.',
            'text.max' => 'The text may not be greater than 5,000 characters.',
            'categories.required' => 'The categories field is required to specify classification options.',
            'categories.string' => 'The categories must be a valid string.',
            'categories.min' => 'The categories must be at least 3 characters long.',
            'categories.max' => 'The categories may not be greater than 500 characters.',
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
            'text' => 'text to classify',
            'categories' => 'classification categories',
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
