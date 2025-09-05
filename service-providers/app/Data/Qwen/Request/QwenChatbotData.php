<?php

namespace App\Data\Qwen\Request;

use Spatie\LaravelData\Data;

class QwenChatbotData extends Data
{
    public function __construct(
        public string $model,
        public array $conversation_history,
        public ?float $temperature = null,
        public ?int $max_tokens = null,
        public ?string $endpoint_interface = 'generate',
    ) {}
}
