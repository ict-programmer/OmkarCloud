<?php

namespace App\Data\Request\DeepSeek;

use Spatie\LaravelData\Data;

class CodeCompletionData extends Data
{
    public function __construct(
        public string $model,
        public string $prompt,
        public int $max_tokens,
        public float $temperature,
        /**
         * @var array<int, string>
         */
        public ?array $attachments = []
    ) {}
}
