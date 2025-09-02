<?php

namespace App\Http\Requests\FFMpeg;

use Illuminate\Foundation\Http\FormRequest;

class StreamCopyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'input' => 'required|url',
            'streams' => 'required|array|min:1|max:10',
            'streams.*' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if ($value === 'all') {
                        return; // 'all' is valid
                    }
                    
                    // Check if it matches pattern like 'video:0', 'audio:1', etc.
                    if (!preg_match('/^(video|audio|subtitle|data):\d+$/', $value)) {
                        $fail(__('Stream format must be like "video:0", "audio:1", "subtitle:0", "data:0", or "all".'));
                    }
                }
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'input.required' => __('Input file URL is required.'),
            'input.url' => __('Input must be a valid URL.'),
            'streams.required' => __('Streams selection is required.'),
            'streams.array' => __('Streams must be an array.'),
            'streams.min' => __('At least one stream must be specified.'),
            'streams.max' => __('Maximum 10 streams can be specified.'),
            'streams.*.required' => __('Each stream specification is required.'),
        ];
    }
}
