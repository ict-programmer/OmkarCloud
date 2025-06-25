<?php

namespace App\Data\Request\Freepik;

use Spatie\LaravelData\Data;

class RelightImageData extends Data
{
    public function __construct(
        public string $image,
        public ?string $prompt,
        public ?string $transfer_light_from_reference_image,
        public ?string $transfer_light_from_lightmap,
        public ?int $light_transfer_strength,
        public ?bool $interpolate_from_original,
        public ?bool $change_background,
        public ?string $style,
        public ?bool $preserve_details,
        public ?array $advanced_settings
    ) {}
}
