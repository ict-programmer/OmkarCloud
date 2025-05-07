<?php

namespace App\Data\Request\ChatGPT;

use Spatie\LaravelData\Data;

class ChatCompletionData extends Data
{
    public function __construct(
        public string $model,
        public string $messages,
        public float $temperature,
        public int $max_tokens,
    ) {}
}
