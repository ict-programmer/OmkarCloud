<?php

namespace App\Http\Requests\Whisper;

use Illuminate\Foundation\Http\FormRequest;

class AudioTranscribeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'link' => 'nullable|string',
            'file' => 'nullable|mimes:mp3,wav,flac,aac,opus,ogg,m4a,mp4,mpeg,mov,webm|max:100000',
            'language' => 'required|string',
            'prompt' => 'required|string',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->file && $this->link) {
                $validator->errors()->add('file', 'You can only upload a file or provide a link, not both.');
            }
            if (!$this->file && !$this->link) {
                $validator->errors()->add('file', 'You must upload a file or provide a link.');
            }
        });
    }
}
