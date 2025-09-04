<?php

namespace App\Http\Requests\FFMpeg;

use Illuminate\Foundation\Http\FormRequest;

class AudioResampleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'input' => 'required|url',
            'sample_rate' => 'required|integer|min:8000|max:192000',
            'channels' => 'required|integer|min:1|max:8',
        ];
    }

    public function messages(): array
    {
        return [
            'input.required' => __('Input audio file URL is required.'),
            'input.url' => __('Input must be a valid URL.'),
            'sample_rate.required' => __('Sample rate is required.'),
            'sample_rate.integer' => __('Sample rate must be an integer.'),
            'sample_rate.min' => __('Sample rate must be at least 8000 Hz.'),
            'sample_rate.max' => __('Sample rate must not exceed 192000 Hz.'),
            'channels.required' => __('Number of channels is required.'),
            'channels.integer' => __('Number of channels must be an integer.'),
            'channels.min' => __('Number of channels must be at least 1.'),
            'channels.max' => __('Number of channels must not exceed 8.'),
        ];
    }
}
