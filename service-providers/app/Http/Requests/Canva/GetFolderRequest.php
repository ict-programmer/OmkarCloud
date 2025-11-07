<?php

namespace App\Http\Requests\Canva;

use Illuminate\Foundation\Http\FormRequest;

class GetFolderRequest extends FormRequest
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
            'endpoint_interface' => ['nullable', 'string', 'in:generate'],
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
            'endpoint_interface.required' => __('The endpoint interface field is required.'),
            'endpoint_interface.string' => __('The endpoint interface must be a string.'),
        ];
    }
}
