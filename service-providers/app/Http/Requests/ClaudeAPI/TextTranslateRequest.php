<?php

namespace App\Http\Requests\ClaudeAPI;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TextTranslateRequest extends FormRequest
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
            'text' => ['required', 'string', 'min:1', 'max:5000'],
            'source_language' => ['required', 'string', 'size:2', 'regex:/^[a-z]{2}$/'],
            'target_language' => ['required', 'string', 'size:2', 'regex:/^[a-z]{2}$/'],
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
            'text.required' => 'The text field is required for translation.',
            'text.string' => 'The text must be a valid text string.',
            'text.min' => 'The text must be at least 1 character long.',
            'text.max' => 'The text may not be greater than 5,000 characters.',
            'source_language.required' => 'The source_language field is required to specify the original language.',
            'source_language.string' => 'The source_language must be a valid string.',
            'source_language.size' => 'The source_language must be exactly 2 characters (ISO 639-1 format).',
            'source_language.regex' => 'The source_language must be a valid 2-letter language code (e.g., en, es, fr).',
            'target_language.required' => 'The target_language field is required to specify the target language.',
            'target_language.string' => 'The target_language must be a valid string.',
            'target_language.size' => 'The target_language must be exactly 2 characters (ISO 639-1 format).',
            'target_language.regex' => 'The target_language must be a valid 2-letter language code (e.g., en, es, fr).',
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
            'text' => 'text to translate',
            'source_language' => 'source language',
            'target_language' => 'target language',
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
