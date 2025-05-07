<?php

namespace App\Data\Request\Gemini;

use Spatie\LaravelData\Data;

class CodeGenerationData extends Data
{
    public function __construct(
        public string $model,
        public string $prompt,
        public int $max_tokens,
        public string $temperature,
        public ?array $attachments
    ) {}
}
