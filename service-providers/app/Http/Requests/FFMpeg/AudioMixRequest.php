<?php

namespace App\Http\Requests\FFMpeg;

use Illuminate\Foundation\Http\FormRequest;

class AudioMixRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'audio_tracks' => 'required|array|min:2|max:8',
            'audio_tracks.*' => 'required|url',
        ];
    }

    public function messages(): array
    {
        return [
            'audio_tracks.required' => __('Audio tracks are required.'),
            'audio_tracks.array' => __('Audio tracks must be an array.'),
            'audio_tracks.min' => __('At least 2 audio tracks are required for mixing.'),
            'audio_tracks.max' => __('Maximum of 8 audio tracks allowed for mixing.'),
            'audio_tracks.*.required' => __('Each audio track is required.'),
            'audio_tracks.*.url' => __('Each audio track must be a valid URL.'),
        ];
    }


}
