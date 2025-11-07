<?php

namespace App\Data\Request\Freepik;

use Spatie\LaravelData\Data;

class ClassicFastGenerateData extends Data
{
    public function __construct(
        public string $prompt,
        public ?string $negative_prompt = null,
        public ?float $guidance_scale = 1.0,
        public ?int $seed = null,
        public ?int $num_images = 1,
        public ?bool $filter_nsfw = true,
        public ?array $image = null,
        public ?array $styling = null,
    ) {}
}
