<?php

namespace App\Http\Requests\PremierPro;

use Illuminate\Foundation\Http\FormRequest;

class ReframeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'video_cid' => 'required|string',
            'scene_detection' => 'nullable|boolean',
            'output_config' => 'required',
            'output_config.aspect_ratios' => 'required|array',
            'output_config.aspect_ratios.*' => 'required|string|regex:/^[0-9]+:[0-9]+$/|unique:output_config.aspect_ratios',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'video_cid.required' => __('Video URL is required'),
            'scene_detection.required' => __('Scene detection is required'),
            'output_config.required' => __('Output config is required'),
            'output_config.aspect_ratios.required' => __('Aspect ratios is required'),
            'output_config.aspect_ratios.*.required' => __('Aspect ratio is required'),
            'output_config.aspect_ratios.*.regex' => __('Aspect ratio must be in the format of width:height'),
            'output_config.aspect_ratios.*.unique' => __('Aspect ratio must be unique'),
        ];
    }
}
