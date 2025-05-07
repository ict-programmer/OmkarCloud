<?php

namespace App\Data\Request\ChatGPT;

use Spatie\LaravelData\Data;

class CodeCompletionData extends Data
{
    public function __construct(
        public string $model,
        public string $description,
        public float $temperature,
        public int $max_tokens,
        public ?array $attachments
    ) {}
}
