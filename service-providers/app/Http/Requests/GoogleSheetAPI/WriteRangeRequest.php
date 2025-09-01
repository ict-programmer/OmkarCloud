<?php

namespace App\Http\Requests\GoogleSheetAPI;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class WriteRangeRequest extends FormRequest
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
            'range' => ['required', 'string'],
            'valueInputOption' => ['required', 'string', Rule::in(['RAW', 'USER_ENTERED'])],
            'values' => ['required', 'array'],
            'values.*' => ['array'],
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
            'range.required' => __('The range is required.'),
            'range.string' => __('The range must be a string.'),
            'valueInputOption.required' => __('The value input option is required.'),
            'valueInputOption.string' => __('The value input option must be a string.'),
            'valueInputOption.in' => __('The selected value input option is invalid. Must be RAW or USER_ENTERED.'),
            'values.required' => __('The values are required.'),
            'values.array' => __('The values must be an array.'),
            'values.*.array' => __('Each item in values must be an array.'),
        ];
    }

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