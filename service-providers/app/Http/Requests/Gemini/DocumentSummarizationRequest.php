<?php

namespace App\Http\Requests\Gemini;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DocumentSummarizationRequest extends FormRequest
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
            'document_text' => ['required', 'string', 'max:100000'],
            'model' => ['required', 'in:gemini-pro,gemini-ultra,gemini-1.5-pro'],
            'summary_length' => ['required', 'integer', 'min:1', 'max:20000'],
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
            'document_text.max' => __('The document text may not be greater than 100,000 characters.'),
            'model.required' => __('The model field is required.'),
            'model.in' => __('The selected model is invalid. Please choose either gemini-pro or gemini-ultra.'),
            'summary_length.required' => __('The summary length field is required.'),
            'summary_length.integer' => __('The summary length must be an integer.'),
            'summary_length.min' => __('The summary length must be at least 1.'),
            'summary_length.max' => __('The summary length may not be greater than 20,000.'),
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
