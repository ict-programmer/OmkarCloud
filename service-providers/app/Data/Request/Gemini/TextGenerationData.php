<?php

namespace App\Data\Request\Gemini;

use Spatie\LaravelData\Data;

class TextGenerationData extends Data
{
    public function __construct(
        public string $model,
        public string $prompt,
        public int $max_tokens,
        public string $temperature,
    ) {}
}
