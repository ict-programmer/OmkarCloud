<?php

namespace App\Data\Qwen\Request;

use Spatie\LaravelData\Data;

class QwenTextSummarizationData extends Data
{
    public function __construct(
        public string $model,
        public string $text,
        public ?int $text_length = null,
        public ?int $max_tokens = null,
        public ?float $temperature = null,
        public ?string $endpoint_interface = 'generate',
    ) {}
}
