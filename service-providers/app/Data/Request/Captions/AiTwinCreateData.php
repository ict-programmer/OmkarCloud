<?php

namespace App\Data\Request\Captions;

use Spatie\LaravelData\Data;

class AiTwinCreateData extends Data
{
    public function __construct(
        public string $name,
        public string $videoUrl,
        public array $calibrationImageUrls,
        public ?string $language,
    ) {}
}
