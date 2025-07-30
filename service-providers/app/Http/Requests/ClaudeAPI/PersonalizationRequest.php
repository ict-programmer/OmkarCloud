<?php

namespace App\Http\Requests\ClaudeAPI;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PersonalizationRequest extends FormRequest
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
            'user_id' => ['required', 'string', 'min:1', 'max:100'],
            'preferences' => ['required', 'string'],
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
            'user_id.required' => 'The user_id field is required for personalization.',
            'user_id.string' => 'The user_id must be a valid string.',
            'user_id.min' => 'The user_id must be at least 1 character long.',
            'user_id.max' => 'The user_id may not be greater than 100 characters.',
            'preferences.required' => 'The preferences field is required for personalization.',
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
            'user_id' => 'user identifier',
            'preferences' => 'user preferences',
            'max_tokens' => 'maximum tokens',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }
}
