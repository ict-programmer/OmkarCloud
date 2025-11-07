<?php

namespace App\Data\Request\Freepik;

use App\Traits\PubliishIOTrait;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Data;

class MysticGenerateData extends Data
{
    use PubliishIOTrait;

    #[Computed]
    public ?string $structure_reference;

    #[Computed]
    public ?string $style_reference;

    public function __construct(
        public string $prompt,
        #[Hidden]
        public ?string $structure_reference_cid = null,
        public ?int $structure_strength = null,
        #[Hidden]
        public ?string $style_reference_cid = null,
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
    ) {

        if ($structure_reference_cid) {
            $this->structure_reference = $this->getPublishUrl($structure_reference_cid);
        }

        if ($style_reference_cid) {
            $this->style_reference = $this->getPublishUrl($style_reference_cid);
        }
    }
}
