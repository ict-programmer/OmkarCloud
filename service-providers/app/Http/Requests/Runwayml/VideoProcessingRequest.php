<?php

namespace App\Http\Requests\Runwayml;

use Illuminate\Foundation\Http\FormRequest;

class VideoProcessingRequest extends FormRequest
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
            'model' => [
                'required',
                'string',
                'in:gen4_turbo,gen3a_turbo',
            ],
            'prompt_image' => 'required|string',
            'prompt_text' => 'required|string|max:1000',
            'seed' => 'required|integer',
            'duration' => 'required|integer|in:5,10',
            'width' => 'required|integer|in:1280,720,1104,832,960,1584,768',
            'height' => 'required|integer|in:720,1280,832,1104,960,672,768',
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
            'model.required' => __('The model field is required.'),
            'model.string' => __('The model must be a string.'),
            'model.in' => __('The selected model is invalid. Allowed values are: gen4_turbo, gen3a_turbo.'),

            'prompt_image.required' => __('The prompt image field is required.'),
            'prompt_image.string' => __('The prompt image must be a string.'),

            'prompt_text.required' => __('The prompt text field is required.'),
            'prompt_text.string' => __('The prompt text must be a string.'),
            'prompt_text.max' => __('The prompt text may not be greater than 1000 characters.'),

            'seed.required' => __('The seed field is required.'),
            'seed.integer' => __('The seed must be an integer.'),

            'duration.required' => __('The duration field is required.'),
            'duration.integer' => __('The duration must be an integer.'),
            'duration.in' => __('The selected duration is invalid. Allowed values are: 5, 10.'),

            'width.required' => __('The width field is required.'),
            'width.integer' => __('The width must be an integer.'),
            'width.in' => __('The selected width is invalid. Allowed values are: 1280, 720, 1104, 832, 960, 1584, 768.'),

            'height.required' => __('The height field is required.'),
            'height.integer' => __('The height must be an integer.'),
            'height.in' => __('The selected height is invalid. Allowed values are: 720, 1280, 832, 1104, 960, 672, 768.'),
        ];
    }
}
