<?php

namespace App\Data\Request\Freepik;

use App\Enums\Freepik\KlingElementModelEnum;
use App\Traits\PubliishIOTrait;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Data;

class KlingElementsVideoData extends Data
{
    use PubliishIOTrait;

    #[Computed]
    public array $images;

    public function __construct(
        public KlingElementModelEnum $model,
        #[Hidden]
        public array $image_cids,
        public ?string $prompt,
        public ?string $negative_prompt,
        public ?string $duration,
        public ?string $aspect_ratio,

    ) {
        if (!empty($image_cids)) {
            $this->images = array_map([$this, 'getPublishUrl'], $image_cids);
        }
    }
}
