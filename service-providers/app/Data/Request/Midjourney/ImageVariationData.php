<?php

namespace App\Data\Request\Midjourney;

use Spatie\LaravelData\Data;

class ImageVariationData extends Data
{
    public function __construct(
        public string $input_image,
        public float $variation_strength,
        public int $count,
        public ?float $guidance_scale = null,
    ) {}
} 