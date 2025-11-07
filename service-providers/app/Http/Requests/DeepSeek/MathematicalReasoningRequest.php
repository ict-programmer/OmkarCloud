<?php

namespace App\Http\Requests\DeepSeek;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MathematicalReasoningRequest extends FormRequest
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
            'problem_statement' => ['required', 'string', 'min:1', 'max:100000'],
            'model' => ['required', 'in:deepseek-chat'],
            'max_tokens' => ['required', 'integer', 'min:1', 'max:5000'],
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
            'problem_statement.required' => __('The problem statement is required.'),
            'problem_statement.string' => __('The problem statement must be a string.'),
            'problem_statement.min' => __('The problem statement must be at least 1 character.'),
            'problem_statement.max' => __('The problem statement may not be greater than 100000 characters.'),
            'model.required' => __('The model field is required.'),
            'model.in' => __('The selected model is invalid. Only "deepseek-chat" is allowed.'),
            'max_tokens.required' => __('The max tokens field is required.'),
            'max_tokens.integer' => __('The max tokens must be an integer.'),
            'max_tokens.min' => __('The max tokens must be at least 1.'),
            'max_tokens.max' => __('The max tokens may not be greater than 5000.'),
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
