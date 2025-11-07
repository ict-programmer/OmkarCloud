<?php

namespace App\Data\Request\DeepSeek;

use Spatie\LaravelData\Data;

class ChatCompletionData extends Data
{
    public function __construct(
        public string $model,
        public array $messages,
        public int  $max_tokens,
        public float  $temperature,
    ) {}
}
