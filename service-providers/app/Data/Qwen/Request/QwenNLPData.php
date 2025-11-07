<?php

namespace App\Data\Qwen\Request;

use Spatie\LaravelData\Data;

class QwenNLPData extends Data
{
    public function __construct(
        public string $model,
        public string $prompt,
        public ?int $max_tokens = null,
        public ?float $temperature = null,
        public ?string $endpoint_interface = 'generate',
    ) {}
}
