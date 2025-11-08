<?php

namespace App\Http\Requests\Canva;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFolderRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'min:1',
                'max:255',
            ],
            'folder_id' => [
                'required',
                'string',
            ],
            'endpoint_interface' => ['nullable', 'string', 'in:generate'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => __('The folder name is required.'),
            'name.string' => __('The folder name must be a text string.'),
            'name.min' => __('The folder name cannot be empty.'),
            'name.max' => __('The folder name cannot exceed 255 characters.'),
            'folder_id.required' => __('The parent folder ID is required.'),
            'folder_id.string' => __('The parent folder ID must be a string.'),
            'folder_id.min' => __('The parent folder ID must contain at least 1 character.'),
            'folder_id.max' => __('The parent folder ID cannot exceed 50 characters.'),
            'endpoint_interface.required' => __('The endpoint interface is required.'),
            'endpoint_interface.string' => __('The endpoint interface must be a string.'),
            'endpoint_interface.in' => __('The endpoint interface must be "generate".'),
        ];
    }
}
