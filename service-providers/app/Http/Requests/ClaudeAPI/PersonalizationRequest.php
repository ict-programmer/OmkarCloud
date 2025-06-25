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
            'preferences' => ['required', 'array', 'min:1', 'max:20'],
            'preferences.*' => ['required', 'string', 'min:2', 'max:50'],
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
            'preferences.array' => 'The preferences must be an array of interests.',
            'preferences.min' => 'The preferences array must contain at least 1 preference.',
            'preferences.max' => 'The preferences array may not contain more than 20 preferences.',
            'preferences.*.required' => 'Each preference must be a valid string.',
            'preferences.*.string' => 'Each preference must be a valid string.',
            'preferences.*.min' => 'Each preference must be at least 2 characters long.',
            'preferences.*.max' => 'Each preference may not be greater than 50 characters.',
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
