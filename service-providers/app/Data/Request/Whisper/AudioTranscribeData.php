<?php

namespace App\Data\Request\Whisper;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class AudioTranscribeData extends Data
{
    public function __construct(
        public ?UploadedFile $file,
        public ?string $link,
        public string $language,
        public string $prompt,
    ) {}
}
