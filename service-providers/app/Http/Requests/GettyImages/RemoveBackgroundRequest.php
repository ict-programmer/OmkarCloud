<?php

namespace App\Http\Requests\GettyImages;

use Illuminate\Foundation\Http\FormRequest;

class RemoveBackgroundRequest extends FormRequest
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
            'reference_asset_id' => ['required', 'string'],
            'reference_generation.generation_request_id' => ['required_with:reference_generation', 'string'],
            'reference_generation.index' => ['required_with:reference_generation', 'integer'],
            'product_id' => ['sometimes', 'integer'],
            'project_code' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
