<?php

namespace App\Http\Requests\FFMpeg;

use Illuminate\Foundation\Http\FormRequest;

class BitrateControlRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'input' => 'required|url',
            'crf' => 'required|integer|min:0|max:51',
            'preset' => 'required|string|in:ultrafast,superfast,veryfast,faster,fast,medium,slow,slower,veryslow',
            'cbr' => 'required|string|regex:/^\d+[kmKM]?$/',
        ];
    }

    public function messages(): array
    {
        return [
            'input.required' => __('Input video file URL is required.'),
            'input.url' => __('Input must be a valid URL.'),
            'crf.required' => __('CRF is required.'),
            'crf.integer' => __('CRF must be an integer.'),
            'crf.min' => __('CRF must be between 0 and 51.'),
            'crf.max' => __('CRF must be between 0 and 51.'),
            'preset.required' => __('Preset is required.'),
            'preset.in' => __('Preset must be one of: ultrafast, superfast, veryfast, faster, fast, medium, slow, slower, veryslow.'),
            'cbr.required' => __('CBR is required.'),
            'cbr.regex' => __('CBR must be a valid bitrate (e.g., 2000k, 5M, 1000).'),
        ];
    }
}
