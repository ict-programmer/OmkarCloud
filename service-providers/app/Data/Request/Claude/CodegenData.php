<?php

namespace App\Data\Request\Claude;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

/**
 * Handles the processing of code generation data.
 *
 * @param UploadedFile[] $attachments An array of files or ClassFile objects to be processed.
 */
class CodegenData extends Data
{
    public function __construct(
        public string $description,
        public int $max_tokens,
        public ?string $model = null,
        public ?string $version = null,
        /**
         * @var array<int, string>
         */
        public ?array $attachments,
    ) {}
}
