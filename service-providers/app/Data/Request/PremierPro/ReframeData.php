<?php

namespace App\Data\Request\PremierPro;

use Spatie\LaravelData\Data;

class ReframeData extends Data
{
    public function __construct(
        public string $video_url,
        public array $output_config,
        public ?bool $scene_detection = true
    ) {}
}
