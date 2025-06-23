<?php

namespace App\Data\Request\Freepik;

use App\Enums\Freepik\KlingModelEnum;
use Spatie\LaravelData\Data;

class KlingImageToVideoData extends Data
{
    public function __construct(
        public KlingModelEnum $model,
        public string $duration,
        public ?string $image,
        public ?string $image_tail,
        public ?string $prompt,
        public ?string $negative_prompt,
        public ?float $cfg_scale,
        public ?string $static_mask,
        public ?array $dynamic_masks,
    ) {}
}
