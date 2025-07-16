<?php

namespace App\Http\Requests\GettyImages;

use Illuminate\Foundation\Http\FormRequest;

class ReplaceBackgroundRequest extends FormRequest
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
            'prompt' => 'nullable|string',
            'reference_asset_id' => 'nullable|string',
            'reference_generation' => 'nullable|array',
            'reference_generation.generation_request_id' => 'nullable|string',
            'reference_generation.index' => 'nullable|integer',
            'product_id' => 'nullable|integer',
            'media_type' => 'nullable|string|in:photography,illustration,vector',
            'negative_prompt' => 'nullable|string',
            'seed' => 'nullable|integer',
            'background_color' => 'nullable|string',
            'project_code' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }
}
