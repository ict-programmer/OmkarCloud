<?php

namespace App\Data\Request\Freepik;

use Spatie\LaravelData\Data;

class UpscaleImageData extends Data
{
    public function __construct(
        public string $image,
        public ?string $scale_factor = null,
        public ?string $optimized_for = null,
        public ?string $prompt = null,
        public ?int $creativity = 0,
        public ?int $hdr = 0,
        public ?int $resemblance = 0,
        public ?int $fractality = 0,
        public ?string $engine = null,
    ) {}
}
