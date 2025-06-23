<?php

namespace App\Http\Requests\Freepik;

use Illuminate\Foundation\Http\FormRequest;

class KlingVideoRequest extends FormRequest
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
            'duration' => ['required', 'in:5,10'],
            'image' => ['nullable', 'string'], // base64 or url
            'prompt' => ['required_without:image', 'string', 'max:2500'],
            'negative_prompt' => ['nullable', 'string', 'max:2500'],
            'cfg_scale' => ['nullable', 'numeric', 'between:0,1'],
            'static_mask' => ['nullable', 'string'], // base64 or url
            'aspect_ratio' => ['nullable', 'in:widescreen_16_9,social_story_9_16,square_1_1'],

            'dynamic_masks' => ['nullable', 'array'],
            'dynamic_masks.*.mask' => ['required_with:dynamic_masks', 'string'],
            'dynamic_masks.*.trajectories' => ['required_with:dynamic_masks', 'array'],
            'dynamic_masks.*.trajectories.*.x' => ['required_with:dynamic_masks', 'integer'],
            'dynamic_masks.*.trajectories.*.y' => ['required_with:dynamic_masks', 'integer'],
        ];
    }
}
