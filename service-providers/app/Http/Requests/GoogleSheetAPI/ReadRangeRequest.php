<?php

namespace App\Http\Requests\GoogleSheetAPI;

class ReadRangeRequest extends GoogleSheetsAPIRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'spreadSheetId' => ['required', 'string'],
            'range' => ['required', 'string'],
            'majorDimensions' => ['nullable', 'string', 'in:ROWS,COLUMNS'],
            'valueRenderOption' => ['nullable', 'string', 'in:FORMATTED_VALUE,UNFORMATTED_VALUE,FORMULA'],
            'dateTimeRenderOption' => ['nullable', 'string', 'in:SERIAL_NUMBER,FORMATTED_STRING']
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
            'majorDimensions.string' => __('The major dimensions must be a string.'),
            'majorDimensions.in' => __('The major dimensions must be either ROWS or COLUMNS.'),
            'valueRenderOption.string' => __('The value render option must be a string.'),
            'valueRenderOption.in' => __('The value render option must be one of FORMATTED_VALUE, UNFORMATTED_VALUE, or FORMULA.'),
            'dateTimeRenderOption.string' => __('The date time render option must be a string.'),
            'dateTimeRenderOption.in' => __('The date time render option must be either SERIAL_NUMBER or FORMATTED_STRING.'),
        ];
    }

}
