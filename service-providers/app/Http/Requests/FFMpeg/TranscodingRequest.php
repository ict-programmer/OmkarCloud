<?php

namespace App\Http\Requests\FFMpeg;

use Illuminate\Foundation\Http\FormRequest;

class TranscodingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file_link' => 'required|url',
            'output_format' => 'nullable|string|in:mp4,avi,mov,mkv,webm,flv,wmv,m4v,3gp,mp3,wav,flac,aac,ogg,m4a,wma',
        ];
    }
}
