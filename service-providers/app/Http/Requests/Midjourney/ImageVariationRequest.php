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
            'origin_task_id' => 'required|string|uuid',
            'index' => 'required|string|in:1,2,3,4,high_variation,low_variation',
            'prompt' => 'required|string|min:1|max:4000',
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
            'origin_task_id.required' => 'The parent task ID is required for generating variations.',
            'origin_task_id.uuid' => 'The origin task ID must be a valid UUID format.',
            'index.required' => 'Image index is required to specify which image to vary.',
            'index.in' => 'Index must be one of: 1, 2, 3, 4, high_variation, low_variation.',
            'prompt.required' => 'A prompt is required for the variation operation.',
            'prompt.min' => 'The prompt must be at least 1 character long.',
            'prompt.max' => 'The prompt cannot exceed 4000 characters.',
        ];
    }
} 