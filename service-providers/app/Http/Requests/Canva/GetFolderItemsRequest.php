<?php

namespace App\Http\Requests\Canva;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetFolderItemsRequest extends FormRequest
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
            'folder_id' => ['required', 'string'],
            'continuation' => [
                'nullable',
                'string',
            ],
            'item_types' => [
                'nullable',
                'array',
            ],
            'item_types.*' => [
                'string',
                Rule::in(['design', 'folder', 'image']),
            ],
            'sort_by' => [
                'nullable',
                'string',
                Rule::in([
                    'created_ascending',
                    'created_descending',
                    'modified_ascending',
                    'modified_descending',
                    'title_ascending',
                    'title_descending',
                ]),
            ],
        ];
    }

    /**
     * Get the validation messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'folder_id.required' => __('The folder_id field is required.'),
            'folder_id.string' => __('The folder_id must be a string.'),
            'continuation.string' => __('The continuation token must be a string.'),
            'item_types.array' => __('The item types must be provided as an array.'),
            'item_types.*.string' => __('Each item type must be a string.'),
            'item_types.*.in' => __('Invalid item type. Allowed types are: design, folder, and image.'),
            'sort_by.string' => __('The sort by parameter must be a string.'),
            'sort_by.in' => __('Invalid sort option. Available options are: created_ascending, created_descending, modified_ascending, modified_descending, title_ascending, title_descending.'),
        ];
    }
}
