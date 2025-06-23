<?php

namespace App\Data\Request\Freepik;

use Spatie\LaravelData\Data;

class KlingVideoData extends Data
{
    public function __construct(
        public string $duration,
        public ?string $image,
        public ?string $prompt,
        public ?string $negative_prompt,
        public ?float $cfg_scale,
        public ?string $aspect_ratio,
        public ?string $static_mask,
        public ?array $dynamic_masks,
    ) {}
}
