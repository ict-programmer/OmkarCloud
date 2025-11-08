<?php

namespace App\Data\Request\Whisper;

use Spatie\LaravelData\Data;

class AudioTranscribeData extends Data
{
    public function __construct(
        public string $link,
        public string $language,
        public string $prompt,
    ) {}
}
