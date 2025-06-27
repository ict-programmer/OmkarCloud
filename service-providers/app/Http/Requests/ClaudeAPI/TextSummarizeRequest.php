<?php

namespace App\Http\Requests\ClaudeAPI;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TextSummarizeRequest extends FormRequest
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
            'text' => ['required', 'string', 'min:10', 'max:10000'],
            'summary_length' => ['required', 'string', 'in:short,medium,long'],
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
            'text.required' => 'The text field is required to create a summary.',
            'text.string' => 'The text must be a valid text string.',
            'text.min' => 'The text must be at least 10 characters long to create a meaningful summary.',
            'text.max' => 'The text may not be greater than 10,000 characters.',
            'summary_length.required' => 'The summary_length field is required to specify the desired summary length.',
            'summary_length.string' => 'The summary_length must be a valid string.',
            'summary_length.in' => 'The summary_length must be one of: short, medium, or long.',
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
            'text' => 'text content',
            'summary_length' => 'summary length',
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
