<?php

namespace App\Http\Requests\Canva;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MoveFolderItemRequest extends FormRequest
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
            'item_id' => [
                'required',
                'string',
                'min:1',
                'max:50',
            ],
            'to_folder_id' => [
                'required',
                'string',
                'min:1',
                'max:50',
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
            'item_id.required' => __('The item_id field is required.'),
            'item_id.string' => __('The item_id must be a string.'),
            'item_id.min' => __('The item_id must contain at least 1 character.'),
            'item_id.max' => __('The item_id cannot exceed 50 characters.'),

            'to_folder_id.required' => __('The to_folder_id field is required.'),
            'to_folder_id.string' => __('The to_folder_id must be a string.'),
            'to_folder_id.min' => __('The to_folder_id must contain at least 1 character.'),
            'to_folder_id.max' => __('The to_folder_id cannot exceed 50 characters.'),
        ];
    }
}
