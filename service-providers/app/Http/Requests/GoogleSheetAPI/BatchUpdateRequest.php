<?php

namespace App\Http\Requests\GoogleSheetAPI;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class BatchUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'spreadSheetId' => ['required', 'string'],
            'data' => ['required', 'array'],
            'data.*.range' => ['required', 'string'],
            'data.*.values' => ['required', 'array'],
            'data.*.values.*' => ['array'], 
            'valueInputOption' => ['required', 'string', Rule::in(['RAW', 'USER_ENTERED'])],
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
            'spreadSheetId.required' => __('The spreadsheet ID is required.'),
            'spreadSheetId.string' => __('The spreadsheet ID must be a string.'),
            'data.required' => __('The data for batch update is required.'),
            'data.array' => __('The data for batch update must be an array.'),
            'data.*.range.required' => __('Each data entry must have a range.'),
            'data.*.range.string' => __('The range must be a string.'),
            'data.*.values.required' => __('Each data entry must have values.'),
            'data.*.values.array' => __('The values for each data entry must be an array.'),
            'data.*.values.*.array' => __('Each item in values must be an array (2D array).'),
            'valueInputOption.required' => __('The value input option is required.'),
            'valueInputOption.string' => __('The value input option must be a string.'),
            'valueInputOption.in' => __('The value input option must be either RAW or USER_ENTERED.'),
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json(
                [
                    "status" => "error",
                    "message" => __("Validation failed"),
                    "errors" => $validator->errors(),
                ],
                422,
            ),
        );
    }
}