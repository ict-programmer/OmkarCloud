<?php

namespace App\Data\Request\Gemini;

use Spatie\LaravelData\Data;

class ImageAnalysisData extends Data
{
    public function __construct(
        public string $image_url,
        public string $description_required
    ) {}
}
