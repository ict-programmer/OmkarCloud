<?php

namespace App\Http\Requests\FFMpeg;

use Illuminate\Foundation\Http\FormRequest;

class AudioEncodeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'input' => 'required|url',
            'codec' => 'required|string|in:aac,libmp3lame,flac,libvorbis,pcm_s16le,wmav2,libfdk_aac,libopus',
            'bitrate' => 'required|string|regex:/^\d+[kKmM]?$/',
        ];
    }

    public function messages(): array
    {
        return [
            'input.required' => __('Input audio file URL is required.'),
            'input.url' => __('Input must be a valid URL.'),
            'codec.required' => __('Audio codec is required.'),
            'codec.in' => __('Codec must be one of the supported audio codecs: aac, libmp3lame, flac, libvorbis, pcm_s16le, wmav2, libfdk_aac, libopus.'),
            'bitrate.required' => __('Bitrate is required.'),
            'bitrate.regex' => __('Bitrate must be a valid format (e.g., 128k, 320k, 1M).'),
        ];
    }
}
