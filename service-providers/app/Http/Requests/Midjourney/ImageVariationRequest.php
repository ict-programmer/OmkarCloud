<?php

namespace App\Http\Requests\Midjourney;

use App\Http\Requests\BaseFormRequest;

class ImageVariationRequest extends BaseFormRequest
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
            'input_image' => 'required|string|url',
            'variation_strength' => 'required|numeric|between:0.0,1.0',
            'count' => 'required|integer|between:1,4',
            'guidance_scale' => 'nullable|numeric|between:1.0,20.0',
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
            'input_image.required' => 'An input image URL is required for generating variations.',
            'input_image.url' => 'The input image must be a valid URL.',
            'variation_strength.required' => 'Variation strength is required.',
            'variation_strength.between' => 'Variation strength must be between 0.0 and 1.0.',
            'count.required' => 'The number of variations to generate is required.',
            'count.between' => 'You can generate between 1 and 4 variations.',
            'guidance_scale.between' => 'Guidance scale must be between 1.0 and 20.0.',
        ];
    }
} 