<?php

namespace App\Http\Requests\GoogleSheetAPI;

use Illuminate\Validation\Rule;

class SheetsManagementRequest extends GoogleSheetsAPIRequest
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
            'type' => ['required', 'string',
                Rule::in([
                    'addSheet',
                    'deleteSheet',
                    'copySheet'
                ])
            ],
            'title' => ['nullable', 'string',
                Rule::requiredIf($this->input('type') === 'addSheet')
            ],
            'sheetId' => ['nullable', 'integer',
                Rule::requiredIf(
                    in_array($this->input('type'), ['deleteSheet', 'copySheet'])
                )
            ],
            'destinationSpreadsheetId' => ['nullable', 'string',
                Rule::requiredIf($this->input('type') === 'copySheet')
            ],
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
            'spreadSheetId.required' => __('A spreadsheet ID is required.'),
            'spreadSheetId.string' => __('The spreadsheet ID must be a string.'),
            'type.required' => __('The type of sheet management operation is required.'),
            'type.string' => __('The type must be a string.'),
            'type.in' => __('The selected type is invalid. Valid types are addSheet, deleteSheet, or copySheet.'),
            'title.string' => __('The title must be a string.'),
            'title.required_if' => __('The title is required when adding a new sheet.'),
            'sheetId.integer' => __('The sheet ID must be an integer.'),
            'sheetId.required_if' => __('The sheet ID is required for deleteSheet or copySheet operations.'),
            'destinationSpreadsheetId.string' => __('The destination spreadsheet ID must be a string.'),
            'destinationSpreadsheetId.required_if' => __('The destination spreadsheet ID is required when copying a sheet.'),
        ];
    }

}