<?php

namespace App\Http\Requests\FFMpeg;

use Illuminate\Foundation\Http\FormRequest;

class VideoEncodeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'input' => 'required|url',
            'codec' => 'required|string|in:libx264,libx265,libvpx,libvpx-vp9,libaom-av1,libsvtav1,mpeg4,libxvid,h264_nvenc,hevc_nvenc,h264_videotoolbox,hevc_videotoolbox',
            'params' => 'required|array|min:1',
            'params.*' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    // Validate parameter format: key=value or just key
                    if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_-]*(?:=[a-zA-Z0-9_.-]+)?$/', $value)) {
                        $fail(__('Parameter format must be "key" or "key=value" (e.g., "crf=23", "preset=medium").'));
                    }
                }
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'input.required' => __('Input video file URL is required.'),
            'input.url' => __('Input must be a valid URL.'),
            'codec.required' => __('Video codec is required.'),
            'codec.in' => __('Codec must be one of the supported video codecs.'),
            'params.required' => __('Encoding parameters are required.'),
            'params.array' => __('Parameters must be an array.'),
            'params.min' => __('At least one encoding parameter must be specified.'),
            'params.*.required' => __('Each parameter is required.'),
        ];
    }
}
