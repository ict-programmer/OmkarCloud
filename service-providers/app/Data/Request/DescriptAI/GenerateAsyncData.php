<?php

namespace App\Data\Request\DescriptAI;

use Spatie\LaravelData\Data;

class GenerateAsyncData extends Data
{
    public function __construct(
        public string $text,
        public string $voice_id,
        public string $voice_style_id,
        public ?string $prefix_text,
        public ?string $prefix_audio_cid,
        public ?string $suffix_text,
        public ?string $suffix_audio_cid,
        public ?string $callback_url
    ) {}
}
