<?php

namespace App\Data\Request\Freepik;

use Spatie\LaravelData\Data;

class MysticGenerateData extends Data
{
    public function __construct(
        public string $prompt,
        public ?string $structure_reference = null,
        public ?int $structure_strength = null,
        public ?string $style_reference = null,
        public ?int $adherence = null,
        public ?int $hdr = null,
        public ?string $resolution = null,
        public ?string $aspect_ratio = null,
        public ?string $model = null,
        public ?int $creative_detailing = null,
        public ?string $engine = null,
        public ?bool $fixed_generation = null,
        public ?bool $filter_nsfw = null,
        public ?array $styling = null,
    ) {}
}
