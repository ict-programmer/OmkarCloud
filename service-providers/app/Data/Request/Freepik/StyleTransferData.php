<?php

namespace App\Data\Request\Freepik;

use Spatie\LaravelData\Data;

class StyleTransferData extends Data
{
    public function __construct(
        public string $image,
        public string $reference_image,
        public ?string $prompt = null,
        public ?int $style_strength = 100,
        public ?int $structure_strength = 50,
        public ?bool $is_portrait = false,
        public ?string $portrait_style = 'standard',
        public ?string $portrait_beautifier = null,
        public ?string $flavor = 'faithful',
        public ?string $engine = 'balanced',
        public ?bool $fixed_generation = false,
    ) {}
}
