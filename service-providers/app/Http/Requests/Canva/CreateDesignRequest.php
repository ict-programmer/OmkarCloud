<?php

namespace App\Http\Requests\Canva;

use Illuminate\Foundation\Http\FormRequest;

class CreateDesignRequest extends FormRequest
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
            'design_type' => ['required', 'array'],
            'design_type.type' => ['required', 'string', 'in:custom,preset'],
            'design_type.name' => ['required', 'string'],
            'design_type.width' => ['nullable', 'required_if:design_type.type,custom', 'integer', 'min:40', 'max:8000'],
            'design_type.height' => ['nullable', 'required_if:design_type.type,custom', 'integer', 'min:40', 'max:8000'],
            'asset_id' => ['required', 'string'],
            'title' => ['required', 'string'],
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
            'design_type.required' => __('The design type is required.'),
            'design_type.array' => __('The design type must be an array.'),
            'design_type.type.required' => __('The design type must have a type field.'),
            'design_type.type.string' => __('The type field in design type must be a string.'),
            'design_type.name.required' => __('The design type must have a name field.'),
            'design_type.name.string' => __('The name field in design type must be a string.'),
            'asset_id.required' => __('The asset ID is required.'),
            'asset_id.string' => __('The asset ID must be a string.'),
            'title.required' => __('The title is required.'),
            'title.string' => __('The title must be a string.'),
            'endpoint_interface.required' => __('The endpoint interface is required.'),
            'endpoint_interface.string' => __('The endpoint interface must be a string.'),
            'endpoint_interface.in' => __('The endpoint interface must be "generate".'),
        ];
    }
}
