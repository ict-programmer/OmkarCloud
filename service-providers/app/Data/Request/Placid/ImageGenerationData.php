<?php

namespace App\Data\Request\Placid;

use Spatie\LaravelData\Data;

class ImageGenerationData extends Data
{
    public function __construct(
        public string $template_uuid,
        public array $layers,
    ) {}
}
