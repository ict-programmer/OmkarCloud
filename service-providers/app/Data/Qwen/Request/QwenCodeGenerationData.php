<?php

namespace App\Data\Qwen\Request;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

/**
 * Handles the processing of code generation data.
 *
 * @param UploadedFile[] $attachments An array of files or ClassFile objects to be processed.
 */
class QwenCodeGenerationData extends Data
{
    public function __construct(
        public string $model,
        public string $prompt,
        public ?int $max_tokens = null,
        public ?float $temperature = null,
        public ?string $endpoint_interface = null,
        public ?array $attachments = null,
    ) {}
}
