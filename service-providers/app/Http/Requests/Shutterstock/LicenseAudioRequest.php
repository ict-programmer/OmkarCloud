<?php

namespace App\Http\Requests\Shutterstock;

use Illuminate\Foundation\Http\FormRequest;

class LicenseAudioRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'audio_tracks' => 'required|array|min:1|max:25',
            'audio_tracks.*.audio_id' => 'required|string|max:255',
            'audio_tracks.*.license' => 'required|string|in:audio_platform,premier_music_basic,premier_music_extended,premier_music_pro,premier_music_comp,audio_standard,audio_enhanced',
            'search_id' => 'nullable|string|max:255',
        ];
    }
} 