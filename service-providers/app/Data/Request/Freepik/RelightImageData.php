<?php

namespace App\Data\Request\Freepik;

use App\Traits\PubliishIOTrait;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Data;

class RelightImageData extends Data
{
    use PubliishIOTrait;

    #[Computed]
    public string $image;

    #[Computed]
    public ?string $transfer_light_from_reference_image;

    #[Computed]
    public ?string $transfer_light_from_lightmap;

    public function __construct(
        #[Hidden]
        public string $image_cid,
        public ?string $prompt,
        #[Hidden]
        public ?string $transfer_light_from_reference_image_cid,
        #[Hidden]
        public ?string $transfer_light_from_lightmap_cid,
        public ?int $light_transfer_strength,
        public ?bool $interpolate_from_original,
        public ?bool $change_background,
        public ?string $style,
        public ?bool $preserve_details,
        public ?array $advanced_settings
    ) {
        $this->image = $this->getPublishUrl($this->image_cid);
        if ($this->transfer_light_from_reference_image_cid) {
            $this->transfer_light_from_reference_image = $this->getPublishUrl($this->transfer_light_from_reference_image_cid);
        }
        if ($this->transfer_light_from_lightmap_cid) {
            $this->transfer_light_from_lightmap = $this->getPublishUrl($this->transfer_light_from_lightmap_cid);
        }
    }
}
