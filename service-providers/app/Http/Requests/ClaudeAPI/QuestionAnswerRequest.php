<?php

namespace App\Http\Requests\ClaudeAPI;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class QuestionAnswerRequest extends FormRequest
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
            'question' => ['required', 'string', 'min:3', 'max:1000'],
            'context' => ['required', 'string', 'min:10', 'max:10000'],
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
            'question.required' => 'The question field is required to provide an answer.',
            'question.string' => 'The question must be a valid text string.',
            'question.min' => 'The question must be at least 3 characters long.',
            'question.max' => 'The question may not be greater than 1,000 characters.',
            'context.required' => 'The context field is required to provide relevant information for answering the question.',
            'context.string' => 'The context must be a valid text string.',
            'context.min' => 'The context must be at least 10 characters long to provide meaningful information.',
            'context.max' => 'The context may not be greater than 10,000 characters.',
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
            'question' => 'question',
            'context' => 'context',
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
