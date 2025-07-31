<?php

namespace App\Http\Requests\Gemini;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ImageAnalysisRequest extends FormRequest
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
            'model' => ['required', 'in:gemini-2.5-flash,gemini-2.5-pro,gemini-2.0-flash,gemini-1.5-flash,gemini-1.5-pro'],
            'image_cid' => ['required', 'string'],
            'description_required' => ['required', 'string', 'min:1', 'max:1000'],
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
            'image_cid.required' => __('The image string is required.'),
            'image_cid.string' => __('The image string must be a valid string.'),
            'description_required.required' => __('The description is required.'),
            'description_required.string' => __('The description must be a string.'),
            'description_required.min' => __('The description must be at least 1 character.'),
            'description_required.max' => __('The description may not be greater than 1000 characters.'),
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
