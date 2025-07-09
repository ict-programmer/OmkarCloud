<?php

namespace App\Http\Requests\ClaudeAPI;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CodegenRequest extends FormRequest
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
            'description' => ['required', 'string', 'min:5', 'max:2000'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['nullable', 'string'],
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
            'description.required' => 'The description field is required to generate code.',
            'description.string' => 'The description must be a valid text string.',
            'description.min' => 'The description must be at least 5 characters long to provide meaningful instructions.',
            'description.max' => 'The description may not be greater than 2,000 characters.',
            'attachments.array' => 'The attachments field must be an array of files.',
            'attachments.*.string' => 'Each attachment must be a valid string.',
            'attachments.*.max' => 'Each attachment may not be larger than 30MB.',
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
            'description' => 'code description',
            'attachments' => 'file attachments',
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
