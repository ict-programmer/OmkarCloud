<?php

namespace App\Http\Requests\DeepSeek;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DocumentQaRequest extends FormRequest
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
            'document_text' => ['required', 'string', 'min:10', 'max:100000'],
            'question' => ['required', 'string', 'min:1', 'max:100000'],
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
            'document_text.required' => __('The document text field is required.'),
            'document_text.string' => __('The document text must be a string.'),
            'document_text.min' => __('The document text must be at least 10 characters.'),
            'document_text.max' => __('The document text may not be greater than 100,000 characters.'),
            'question.required' => 'The question field is required.',
            'question.string' => 'The question must be a string.',
            'question.min' => 'The question must be at least 1 character.',
            'question.max' => 'The question may not be greater than 100000 characters.',
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
