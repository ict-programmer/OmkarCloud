<?php

namespace App\Http\Requests\GettyImages;

use Illuminate\Foundation\Http\FormRequest;

class RemoveObjectFromImageRequest extends FormRequest
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
            'reference_asset_id' => 'nullable|string',
            'reference_generation' => 'nullable|array',
            'reference_generation.generation_request_id' => 'nullable|string',
            'reference_generation.index' => 'nullable|integer',
            'product_id' => 'nullable|integer',
            'project_code' => 'nullable|string',
            'notes' => 'nullable|string',
            'mask_url' => 'nullable|url',
        ];
    }
}
