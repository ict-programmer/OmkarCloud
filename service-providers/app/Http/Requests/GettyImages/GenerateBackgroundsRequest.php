<?php

namespace App\Http\Requests\GettyImages;

use Illuminate\Foundation\Http\FormRequest;

class GenerateBackgroundsRequest extends FormRequest
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
            'reference_file_registration_id' => 'nullable|string',
            'prompt' => 'nullable|string',
            'product_id' => 'nullable|integer',
            'project_code' => 'nullable|string',
            'notes' => 'nullable|string',
            'left_percentage' => 'nullable|numeric|min:0|max:100',
            'right_percentage' => 'nullable|numeric|min:0|max:100',
            'top_percentage' => 'nullable|numeric|min:0|max:100',
            'bottom_percentage' => 'nullable|numeric|min:0|max:100',
        ];
    }
}
