<?php

namespace App\Http\Requests\GoogleSheetAPI;

class ClearRangeRequest extends GoogleSheetsAPIRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'spreadSheetId' => ['required', 'string'],
            'range' => ['required', 'string'],
        ]);
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
        ];
    }

}