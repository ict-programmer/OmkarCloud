<?php

namespace App\Data\Request\ChatGPT;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class ChatCompletionData extends Data
{
    public function __construct(
        public string $model,
        public string $messages,
        public float $temperature,
        public int $max_tokens,
        public ?UploadedFile $knowledge_base,
        public ?UploadedFile $schema_tool,
    ) {}
}
