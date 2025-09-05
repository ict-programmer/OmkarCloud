<?php

namespace App\Http\Requests\Midjourney;

use App\Http\Requests\BaseFormRequest;

class ImageGenerationRequest extends BaseFormRequest
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
            'prompt' => 'required|string|min:1|max:4000',
            'aspect_ratio' => 'nullable|string|in:1:1,16:9,9:16,4:3,3:4,3:2,2:3',
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
            'prompt.required' => 'A detailed text prompt is required for image generation.',
            'prompt.min' => 'The prompt must be at least 1 character long.',
            'prompt.max' => 'The prompt cannot exceed 4000 characters.',
            'aspect_ratio.in' => 'The aspect ratio must be one of: 1:1, 16:9, 9:16, 4:3, 3:4, 3:2, 2:3.',
        ];
    }
} 