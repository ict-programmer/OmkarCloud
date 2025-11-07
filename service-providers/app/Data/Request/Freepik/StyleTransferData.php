<?php

namespace App\Data\Request\Freepik;

use App\Traits\PubliishIOTrait;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Data;

class StyleTransferData extends Data
{
    use PubliishIOTrait;

    #[Computed]
    public string $image;

    #[Computed]
    public string $reference_image;

    public function __construct(
        #[Hidden]
        public string $image_cid,
        #[Hidden]
        public string $reference_image_cid,
        public ?string $prompt = null,
        public ?int $style_strength = 100,
        public ?int $structure_strength = 50,
        public ?bool $is_portrait = false,
        public ?string $portrait_style = 'standard',
        public ?string $portrait_beautifier = null,
        public ?string $flavor = 'faithful',
        public ?string $engine = 'balanced',
        public ?bool $fixed_generation = false,
    ) {
        $this->image = $this->getPublishUrl($this->image_cid);
        $this->reference_image = $this->getPublishUrl($this->reference_image_cid);
    }
}
