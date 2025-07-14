<?php

namespace App\Data\Request\Gemini;

use Spatie\LaravelData\Data;

class ImageAnalysisData extends Data
{
    public function __construct(
        public string $image_cid,
        public string $description_required,
        public int $max_tokens
    ) {}
}
