<?php

namespace App\Data\Request\Freepik;

use App\Traits\PubliishIOTrait;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Data;

class UpscaleImageData extends Data
{
    use PubliishIOTrait;

    #[Computed]
    public string $image;

    public function __construct(
        #[Hidden]
        public string $image_cid,
        public ?string $scale_factor = null,
        public ?string $optimized_for = null,
        public ?string $prompt = null,
        public ?int $creativity = 0,
        public ?int $hdr = 0,
        public ?int $resemblance = 0,
        public ?int $fractality = 0,
        public ?string $engine = null,
    ) {
        $this->image = $this->getPublishUrl($this->image_cid);
    }
}
