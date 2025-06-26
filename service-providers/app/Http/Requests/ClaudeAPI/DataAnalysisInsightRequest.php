<?php

namespace App\Http\Requests\ClaudeAPI;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DataAnalysisInsightRequest extends FormRequest
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
            'data' => ['required', 'array', 'min:1', 'max:1000'],
            'data.*' => ['required', 'array'],
            'task' => ['required', 'string', 'min:3', 'max:100'],
            'model' => ['required', 'string'],
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
            'data.required' => 'The data field is required for analysis.',
            'data.array' => 'The data must be an array of objects.',
            'data.min' => 'The data array must contain at least 1 item for analysis.',
            'data.max' => 'The data array may not contain more than 1,000 items.',
            'data.*.required' => 'Each data item must be a valid object.',
            'data.*.array' => 'Each data item must be an object.',
            'task.required' => 'The task field is required to specify the analysis task.',
            'task.string' => 'The task must be a valid string.',
            'task.min' => 'The task must be at least 3 characters long.',
            'task.max' => 'The task may not be greater than 100 characters.',
            'model.required' => 'The model field is required to specify the model to use.',
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
            'data' => 'data array',
            'task' => 'analysis task',
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
