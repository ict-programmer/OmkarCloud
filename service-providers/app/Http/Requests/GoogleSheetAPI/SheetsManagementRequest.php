<?php

namespace App\Http\Requests\GoogleSheetAPI;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SheetsManagementRequest extends FormRequest
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
            'type.required' => __('The operation type is required.'),
            'type.in' => __('The selected operation type is invalid. Valid types are addSheet, deleteSheet, or copySheet.'),
            'title.required_if' => __('The title is required when adding a new sheet.'),
            'sheetId.required_if' => __('The sheet ID is required for deleteSheet or copySheet operations.'),
            'destinationSpreadsheetId.required_if' => __('The destination spreadsheet ID is required when copying a sheet.'),
        ];
    }
}