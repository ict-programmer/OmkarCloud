<?php

namespace App\Data\Request\Freepik;

use Spatie\LaravelData\Data;

class KlingTextToVideoData extends Data
{
    public function __construct(
        public string $duration,
        public ?string $prompt,
        public ?string $negative_prompt,
        public ?float $cfg_scale,
        public ?string $aspect_ratio,
    ) {}
}
