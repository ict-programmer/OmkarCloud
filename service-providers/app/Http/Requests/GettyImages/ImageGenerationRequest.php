<?php

namespace App\Http\Requests\GettyImages;

use Illuminate\Foundation\Http\FormRequest;

class ImageGenerationRequest extends FormRequest
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
            'prompt'        => 'nullable|string',
            'seed'          => 'nullable|integer',
            'aspect_ratio'  => 'nullable|string',
            'media_type'    => 'nullable|string|in:photography',
            'mood'          => 'nullable|string|in:black_and_white,bold,cool,dramatic,natural,vivid,warm',
            'product_id'    => 'nullable|integer',
            'project_code'  => 'nullable|string',
            'notes'         => 'nullable|string',
        ];
    }
}
