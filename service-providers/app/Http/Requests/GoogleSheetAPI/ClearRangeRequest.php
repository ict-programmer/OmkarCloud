<?php

namespace App\Http\Requests\GoogleSheetAPI;

use App\Http\Requests\BaseFormRequest;

class ClearRangeRequest extends BaseFormRequest
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
            'spreadSheetId.string' => __('The spreadsheet ID is required.'),
            'range.required' => __('The range is required.'),
            'range.string' => __('The range must be a string.'),
        ];
    }
}